<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use App\Services\PurchaseEmailService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * These tests use RefreshDatabase, so they only run against an in-memory
 * SQLite database to never wipe the local MySQL database:
 *
 *   DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --filter=EventResendPurchaseEmailsTest
 *
 * Emails go to the "array" mailer (nothing leaves the machine) and the PDF render is stubbed.
 */
class EventResendPurchaseEmailsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Event $event;
    private Event $otherEvent;
    private Ticket $general;
    private Ticket $vip;

    protected function setUp(): void
    {
        if (getenv('DB_CONNECTION') !== 'sqlite' || getenv('DB_DATABASE') !== ':memory:') {
            $this->markTestSkipped('Run with DB_CONNECTION=sqlite DB_DATABASE=:memory: to avoid touching the local database.');
        }

        parent::setUp();

        // Never use the real SMTP mailer from tests.
        config([
            'logging.default' => 'null',
            'mail.default' => 'array',
            'mail.from.address' => 'no-reply@example.com',
        ]);

        // Avoid dompdf work (and remote QR image fetches) in tests.
        $this->partialMock(PurchaseEmailService::class, function (MockInterface $mock) {
            $mock->shouldReceive('renderPdf')->andReturn('%PDF-1.4 fake');
        });

        $this->user = User::create(['username' => 'admin', 'email' => 'admin@example.com', 'password' => 'secret123']);
        $this->event = $this->makeEvent('Tributo de Prueba');
        $this->otherEvent = $this->makeEvent('Otro Evento');
        $this->general = $this->makeTicket($this->event, 'General', '100');
        $this->vip = $this->makeTicket($this->event, 'VIP', '250');
    }

    private function makeEvent(string $title): Event
    {
        return Event::create([
            'title' => $title,
            'ubication' => 'El After Social Club',
            'date_time_start' => '2026-12-05 20:00:00',
            'date_time_end' => '2026-12-06 02:00:00',
            'summary' => 'Resumen',
            'created_by' => $this->user->id,
        ]);
    }

    private function makeTicket(Event $event, string $title, string $price): Ticket
    {
        return Ticket::create([
            'title' => $title,
            'quantity' => '100',
            'type' => 'paid',
            'price' => $price,
            'date_time_start' => '2026-11-01 00:00:00',
            'date_time_end' => '2026-12-05 20:00:00',
            'available' => '1',
            'event_id' => $event->id,
        ]);
    }

    private function makeOrder(Ticket $ticket, string $email, string $name = 'Ana'): Order
    {
        return Order::create([
            'name_buyer' => $name,
            'last_name_buyer' => 'Pérez',
            'email_buyer' => $email,
            'phone_buyer' => '5550000000',
            'code' => Str::random(10),
            'ticket_id' => $ticket->id,
        ]);
    }

    /** @return \Symfony\Component\Mailer\SentMessage[] */
    private function sentMessages(): array
    {
        return Mail::mailer('array')->getSymfonyTransport()->messages()->all();
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('event.resend-emails', $this->event->id))->assertRedirect(route('login'));
        $this->post(route('event.resend-emails.send', $this->event->id), ['order_ids' => [1]])->assertRedirect(route('login'));
        $this->assertCount(0, $this->sentMessages());
    }

    public function test_events_list_has_resend_action(): void
    {
        $this->actingAs($this->user)->get(route('event.index'))
            ->assertOk()
            ->assertSee(route('event.resend-emails', $this->event->id), false)
            ->assertSee('Reenviar emails de compra');
    }

    public function test_view_lists_only_this_event_orders_with_checkboxes(): void
    {
        $a = $this->makeOrder($this->general, 'ana@example.com', 'Ana');
        $b = $this->makeOrder($this->vip, 'beto@example.com', 'Beto');
        $foreign = $this->makeOrder($this->makeTicket($this->otherEvent, 'Otro', '50'), 'otro@example.com', 'Ajeno');

        $this->actingAs($this->user)->get(route('event.resend-emails', $this->event->id))
            ->assertOk()
            ->assertSee('Tributo de Prueba')
            ->assertSee('id="selectAll"', false)
            ->assertSee('name="order_ids[]" value="' . $a->id . '"', false)
            ->assertSee('name="order_ids[]" value="' . $b->id . '"', false)
            ->assertSee('ana@example.com')
            ->assertSee('Beto Pérez')
            ->assertSee('#' . $a->id)
            ->assertSee('Gratis')
            ->assertSee('name="_token"', false)
            ->assertDontSee('otro@example.com')
            ->assertDontSee('value="' . $foreign->id . '"', false);
    }

    public function test_view_is_paginated_and_searchable(): void
    {
        for ($i = 1; $i <= 105; $i++) {
            $this->makeOrder($this->general, "buyer{$i}@example.com", "Buyer{$i}");
        }
        $target = $this->makeOrder($this->vip, 'buscame@example.com', 'Zoe');

        $html = $this->actingAs($this->user)->get(route('event.resend-emails', $this->event->id))
            ->assertOk()
            ->assertSee('Mostrando 1 a 100 de 106 órdenes')
            ->getContent();
        $this->assertSame(100, substr_count($html, 'name="order_ids[]"'));

        $this->actingAs($this->user)->get(route('event.resend-emails', [$this->event->id, 'page' => 2]))
            ->assertOk()
            ->assertSee('Mostrando 101 a 106 de 106 órdenes');

        $html = $this->actingAs($this->user)->get(route('event.resend-emails', [$this->event->id, 'search' => 'BUSCAME']))
            ->assertOk()
            ->assertSee('Mostrando 1 a 1 de 1 órdenes')
            ->getContent();
        $this->assertSame(1, substr_count($html, 'name="order_ids[]"'));
        $this->assertStringContainsString('value="' . $target->id . '"', $html);

        $this->actingAs($this->user)->get(route('event.resend-emails', [$this->event->id, 'search' => '#' . $target->id]))
            ->assertOk()
            ->assertSee('buscame@example.com');

        $this->actingAs($this->user)->get(route('event.resend-emails', [$this->event->id, 'page' => 50]))
            ->assertRedirect(route('event.resend-emails', [$this->event->id, 'page' => 2]));
    }

    public function test_unknown_event_returns_404(): void
    {
        $this->actingAs($this->user)->get(route('event.resend-emails', 999999))->assertNotFound();
    }

    public function test_sends_only_selected_orders_grouped_by_buyer(): void
    {
        $ana1 = $this->makeOrder($this->general, 'ana@example.com', 'Ana');
        $ana2 = $this->makeOrder($this->vip, 'ANA@example.com', 'Ana');
        $beto = $this->makeOrder($this->general, 'beto@example.com', 'Beto');
        $notSelected = $this->makeOrder($this->general, 'carla@example.com', 'Carla');

        $this->actingAs($this->user)
            ->post(route('event.resend-emails.send', $this->event->id), ['order_ids' => [$ana1->id, $ana2->id, $beto->id]])
            ->assertRedirect(route('event.resend-emails', $this->event->id))
            ->assertSessionHas('succes', 'Correos reenviados: 2 (3 órdenes). Fallidos: 0.');

        $messages = $this->sentMessages();
        $this->assertCount(2, $messages);

        $recipients = collect($messages)->map(fn ($m) => strtolower($m->getOriginalMessage()->getTo()[0]->getAddress()))->sort()->values()->all();
        $this->assertSame(['ana@example.com', 'beto@example.com'], $recipients);

        $anaEmail = collect($messages)->first(fn ($m) => strtolower($m->getOriginalMessage()->getTo()[0]->getAddress()) === 'ana@example.com')->getOriginalMessage();
        $this->assertSame('Tributo de Prueba', $anaEmail->getSubject());
        $this->assertSame('Order.pdf', $anaEmail->getAttachments()[0]->getFilename());
        $html = $anaEmail->getHtmlBody();
        $this->assertStringContainsString('1 x General', $html);
        $this->assertStringContainsString('1 x VIP', $html);
        $this->assertStringNotContainsString('carla@example.com', implode(',', $recipients));
    }

    public function test_rejects_orders_from_other_events_and_sends_nothing(): void
    {
        $own = $this->makeOrder($this->general, 'ana@example.com');
        $foreign = $this->makeOrder($this->makeTicket($this->otherEvent, 'Otro', '50'), 'otro@example.com');

        $this->actingAs($this->user)
            ->post(route('event.resend-emails.send', $this->event->id), ['order_ids' => [$own->id, $foreign->id]])
            ->assertRedirect(route('event.resend-emails', $this->event->id))
            ->assertSessionHas('error');

        $this->actingAs($this->user)
            ->post(route('event.resend-emails.send', $this->event->id), ['order_ids' => [999999]])
            ->assertSessionHas('error');

        $this->assertCount(0, $this->sentMessages());
    }

    public function test_validates_selection(): void
    {
        $this->actingAs($this->user)
            ->from(route('event.resend-emails', $this->event->id))
            ->post(route('event.resend-emails.send', $this->event->id), [])
            ->assertSessionHasErrors('order_ids');

        $this->actingAs($this->user)
            ->post(route('event.resend-emails.send', $this->event->id), ['order_ids' => ['abc']])
            ->assertSessionHasErrors('order_ids.0');

        $this->actingAs($this->user)
            ->post(route('event.resend-emails.send', $this->event->id), ['order_ids' => range(1, 101)])
            ->assertSessionHasErrors('order_ids');

        $this->assertCount(0, $this->sentMessages());
    }

    public function test_reports_failed_sends(): void
    {
        $ok = $this->makeOrder($this->general, 'ana@example.com');
        $bad = $this->makeOrder($this->general, 'falla@example.com');

        $this->partialMock(PurchaseEmailService::class, function (MockInterface $mock) {
            $mock->shouldReceive('renderPdf')->andReturn('%PDF-1.4 fake');
            $mock->shouldReceive('send')->andReturnUsing(function (array $emailData) {
                if ($emailData['email'] === 'falla@example.com') {
                    throw new \RuntimeException('SMTP down');
                }
            });
        });

        $this->actingAs($this->user)
            ->post(route('event.resend-emails.send', $this->event->id), ['order_ids' => [$ok->id, $bad->id]])
            ->assertSessionHas('error', fn ($msg) => str_contains($msg, 'Correos reenviados: 1 (1 órdenes). Fallidos: 1.') && str_contains($msg, 'falla@example.com'));
    }

    public function test_resend_command_still_works_with_shared_service(): void
    {
        $first = $this->makeOrder($this->general, 'ana@example.com', 'Ana');
        $second = $this->makeOrder($this->general, 'ana@example.com', 'Ana');

        $this->artisan('tickets:resend-purchase', [
            'ticket_id' => $this->general->id,
            'email' => 'ana@example.com',
            '--dry-run' => true,
        ])->assertSuccessful();
        $this->assertCount(0, $this->sentMessages());

        $this->artisan('tickets:resend-purchase', [
            'ticket_id' => $this->general->id,
            'email' => 'ana@example.com',
            '--to' => 'qa@example.com',
            '--no-interaction' => true,
        ])->assertSuccessful();

        $messages = $this->sentMessages();
        $this->assertCount(1, $messages);
        $email = $messages[0]->getOriginalMessage();
        $this->assertSame('qa@example.com', $email->getTo()[0]->getAddress());
        $this->assertStringContainsString('2 x General', $email->getHtmlBody());
        $this->assertNotNull($first);
        $this->assertNotNull($second);
    }

    public function test_requires_csrf_token(): void
    {
        $order = $this->makeOrder($this->general, 'ana@example.com');

        // Laravel disables CSRF checks while running unit tests; check the middleware is in the web group instead.
        $middleware = app('router')->getRoutes()->getByName('event.resend-emails.send')->gatherMiddleware();
        $this->assertContains('web', $middleware);
        $this->assertContains('auth', $middleware);
        $this->assertContains(\App\Http\Middleware\VerifyCsrfToken::class, app(\Illuminate\Contracts\Http\Kernel::class)->getMiddlewareGroups()['web']);
        $this->assertNotNull($order);
    }
}
