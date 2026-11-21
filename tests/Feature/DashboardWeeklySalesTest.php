<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * These tests use RefreshDatabase, so they only run against an in-memory
 * SQLite database to never wipe the local MySQL database:
 *
 *   DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --filter=DashboardWeeklySalesTest
 */
class DashboardWeeklySalesTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        if (getenv('DB_CONNECTION') !== 'sqlite' || getenv('DB_DATABASE') !== ':memory:') {
            $this->markTestSkipped('Run with DB_CONNECTION=sqlite DB_DATABASE=:memory: to avoid touching the local database.');
        }

        parent::setUp();

        config(['logging.default' => 'null']);
        // Wednesday 18 Nov 2026 => week 2026-W47 (Mon 16 - Sun 22 Nov).
        Carbon::setTestNow(Carbon::parse('2026-11-18 12:00:00', config('app.timezone')));

        $this->user = User::create(['username' => 'admin', 'email' => 'admin@example.com', 'password' => 'secret123']);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function event(string $title, string $start): Event
    {
        return Event::create([
            'title' => $title,
            'ubication' => 'El After Social Club',
            'date_time_start' => $start,
            'date_time_end' => $start,
            'summary' => 'Resumen',
            'created_by' => $this->user->id,
        ]);
    }

    private function ticket(Event $event, ?string $price, string $type = 'paid'): Ticket
    {
        return Ticket::create([
            'title' => $type === 'free' ? 'Free' : 'General',
            'quantity' => '100',
            'type' => $type,
            'price' => $price,
            'date_time_start' => '2026-11-01 00:00',
            'date_time_end' => '2026-11-30 00:00',
            'available' => '1',
            'event_id' => $event->id,
        ]);
    }

    private function orders(Ticket $ticket, int $count, bool $paid): void
    {
        for ($i = 0; $i < $count; $i++) {
            Order::create([
                'name_buyer' => 'Ana',
                'last_name_buyer' => 'Pérez',
                'email_buyer' => 'ana@example.com',
                'phone_buyer' => '1',
                'code' => Str::random(10),
                'stripe_data' => $paid ? json_encode(['id' => 'cs_test', 'amount_total' => 9999]) : null,
                'ticket_id' => $ticket->id,
            ]);
        }
    }

    private function chartRows(string $html): ?array
    {
        if (!preg_match('/const rows = (\[.*?\]);/s', $html, $m)) {
            return null;
        }

        return json_decode($m[1], true);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('home'))->assertRedirect(route('login'));
    }

    public function test_current_week_shows_events_monday_to_sunday_with_sales(): void
    {
        $concert = $this->event('Concierto <Rock> & Más', '2026-11-20 20:00');
        $this->orders($paid = $this->ticket($concert, '25'), 3, true);   // 3 x $25
        $this->orders($this->ticket($concert, '40'), 1, true);           // 1 x $40
        $this->orders($this->ticket($concert, null, 'free'), 2, false);  // 2 free
        $this->orders($paid, 1, false);                                  // priced ticket but not paid with Stripe => free

        $this->event('Lunes temprano', '2026-11-16 00:00');               // first minute of the week
        $this->event('Domingo noche', '2026-11-22 23:59');                // last minute of the week
        $this->event('Domingo anterior', '2026-11-15 23:59');             // previous week
        $this->event('Lunes siguiente', '2026-11-23 00:00');              // next week

        $html = $this->actingAs($this->user)->get(route('home'))
            ->assertOk()
            ->assertSee('Semana del 16 al 22 de noviembre de 2026')
            ->assertSee('Semana actual')
            ->assertSee('$115.00 USD')
            ->assertSee('id="weeklySalesChart"', false)
            ->assertSee('assets/js/plugins/chartjs.min.js', false)
            ->assertSee('id="btnDownloadChart"', false)
            ->assertSee('ventas-semana-2026-W47.png')
            ->assertSee(route('home', ['week' => '2026-W46']), false)
            ->assertSee(route('home', ['week' => '2026-W48']), false)
            ->assertDontSee('Concierto <Rock>', false) // escaped in JSON, no raw HTML
            ->getContent();

        $rows = $this->chartRows($html);
        $this->assertSame(['Lunes temprano', 'Concierto <Rock> & Más', 'Domingo noche'], array_column($rows, 'title'));
        $this->assertEquals(115.0, $rows[1]['revenue']);
        $this->assertSame(4, $rows[1]['paid_tickets']);
        $this->assertSame(3, $rows[1]['free_tickets']);
        $this->assertSame('Vie 20 nov, 20:00', $rows[1]['date_label']);
        $this->assertEquals(0, $rows[0]['revenue']);
    }

    public function test_week_navigation_with_week_and_date_parameters(): void
    {
        $past = $this->event('Evento pasado', '2025-04-16 19:00');
        $this->orders($this->ticket($past, '10'), 2, true);

        $html = $this->actingAs($this->user)->get(route('home', ['week' => '2025-W16']))
            ->assertOk()
            ->assertSee('Semana del 14 al 20 de abril de 2025')
            ->assertDontSee('badge bg-gradient-primary ms-1">Semana actual', false)
            ->assertSee('$20.00 USD')
            ->getContent();
        $this->assertSame(['Evento pasado'], array_column($this->chartRows($html), 'title'));

        $this->actingAs($this->user)->get(route('home', ['date' => '2025-04-20']))
            ->assertOk()
            ->assertSee('Semana del 14 al 20 de abril de 2025');

        // Week crossing months / years.
        $this->actingAs($this->user)->get(route('home', ['week' => '2026-W53']))
            ->assertOk()
            ->assertSee('Semana del 28 de diciembre de 2026 al 3 de enero de 2027');
    }

    public function test_invalid_week_input_falls_back_to_current_week(): void
    {
        $this->actingAs($this->user);

        foreach (['week=abc', 'week[]=1', 'week[a][b]=2', 'week=2025-W53', 'week=2026-W00', 'week=1999-W10', 'week=2026-W99',
                     'date=2026-02-30', 'date=nope', 'date[]=2026-01-01', 'date=1900-01-01'] as $query) {
            $this->get('/dashboard?' . $query)
                ->assertOk()
                ->assertSee('Semana del 16 al 22 de noviembre de 2026');
        }
    }

    public function test_week_without_events_shows_empty_state(): void
    {
        $this->actingAs($this->user)->get(route('home', ['week' => '2026-W48']))
            ->assertOk()
            ->assertSee('No hay eventos con fecha en esta semana.')
            ->assertSee('$0.00 USD')
            ->assertDontSee('id="weeklySalesChart"', false)
            ->assertDontSee('chartjs.min.js');
    }
}
