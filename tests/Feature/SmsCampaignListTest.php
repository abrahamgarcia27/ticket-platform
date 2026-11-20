<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * These tests use RefreshDatabase, so they only run against an in-memory
 * SQLite database to never wipe the local MySQL database:
 *
 *   DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --filter=SmsCampaignListTest
 *
 * The ShortySMS API is faked, no real requests are sent.
 */
class SmsCampaignListTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        if (getenv('DB_CONNECTION') !== 'sqlite' || getenv('DB_DATABASE') !== ':memory:') {
            $this->markTestSkipped('Run with DB_CONNECTION=sqlite DB_DATABASE=:memory: to avoid touching the local database.');
        }

        parent::setUp();

        // 25 campaigns + 1 non-campaign, split across two API pages.
        $campaigns = [];
        for ($i = 1; $i <= 25; $i++) {
            $campaigns[] = $this->campaign($i, "Campaign {$i}", $i === 7 ? 'Promoción especial del aniversario' : "Message {$i}", $i % 2 ? 'VIP list' : 'General list');
        }
        $campaigns[] = array_merge($this->campaign(99, 'Direct message', 'Not a campaign', 'General list'), ['is_campaign' => false]);

        Http::preventStrayRequests();
        Http::fake([
            'shortysms.com/api/campaigns?page=2' => Http::response([
                'data' => array_slice($campaigns, 20),
                'next_page_url' => null,
            ]),
            'shortysms.com/api/campaigns' => Http::response([
                'data' => array_slice($campaigns, 0, 20),
                'next_page_url' => 'https://shortysms.com/api/campaigns?page=2',
            ]),
        ]);
    }

    private function campaign(int $id, string $title, string $content, string $list): array
    {
        return [
            'id' => $id,
            'title' => $title,
            'content' => $content,
            'sent_at' => '2025-09-02T15:31:35.000000Z',
            'is_campaign' => true,
            'contact_lists' => [['name' => $list]],
        ];
    }

    private function user(): User
    {
        return User::create(['username' => 'tester', 'email' => 'tester@example.com', 'password' => 'secret123']);
    }

    public function test_guests_are_redirected(): void
    {
        $this->get(route('sms.index'))->assertRedirect(route('login'));
        $this->get(route('sms.export.csv'))->assertRedirect(route('login'));
        $this->get(route('sms.export.pdf'))->assertRedirect(route('login'));
    }

    public function test_list_is_paginated(): void
    {
        $user = $this->user();

        $this->actingAs($user)->get(route('sms.index'))
            ->assertOk()
            ->assertSee('Showing 1 to 10 of 25 campaigns')
            ->assertSee('Campaign 10')
            ->assertDontSee('>Campaign 11<', false)
            ->assertDontSee('Direct message');

        $this->actingAs($user)->get(route('sms.index', ['page' => 3]))
            ->assertOk()
            ->assertSee('Showing 21 to 25 of 25 campaigns')
            ->assertSee('Campaign 25');
    }

    public function test_search_filters_and_pagination_keeps_query(): void
    {
        $user = $this->user();

        // 13 campaigns in "VIP list" (odd ids).
        $response = $this->actingAs($user)->get(route('sms.index', ['search' => 'vip']))
            ->assertOk()
            ->assertSee('Showing 1 to 10 of 13 campaigns')
            ->assertDontSee('Campaign 2<', false);
        $this->assertStringContainsString('search=vip&amp;page=2', $response->getContent());

        $this->actingAs($user)->get(route('sms.index', ['search' => 'ANIVERSARIO']))
            ->assertOk()
            ->assertSee('Showing 1 to 1 of 1 campaigns')
            ->assertSee('Campaign 7');

        $this->actingAs($user)->get(route('sms.index', ['search' => 'nothing-matches']))
            ->assertOk()
            ->assertSee('No campaigns match your search.');
    }

    public function test_csv_export_respects_search_and_includes_all_pages(): void
    {
        $user = $this->user();

        $response = $this->actingAs($user)->get(route('sms.export.csv', ['search' => 'vip']));
        $response->assertOk();
        $this->assertStringStartsWith('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment; filename=sms-campaigns-', $response->headers->get('Content-Disposition'));

        $csv = $response->streamedContent();
        $this->assertStringStartsWith("\xEF\xBB\xBF", $csv);
        $lines = array_values(array_filter(explode("\n", trim(substr($csv, 3)))));
        $this->assertSame('ID,Title,Content,"Sent At","Contact List"', $lines[0]);
        $this->assertCount(14, $lines); // header + 13 VIP campaigns (both API pages)
        $this->assertStringContainsString('Campaign 25', $csv);
        $this->assertStringNotContainsString('General list', $csv);

        $all = $this->actingAs($user)->get(route('sms.export.csv'))->streamedContent();
        $this->assertStringContainsString('Promoción especial del aniversario', $all);
        $this->assertCount(26, array_filter(explode("\n", trim(substr($all, 3)))));
    }

    public function test_pdf_export_downloads_pdf(): void
    {
        $user = $this->user();

        $response = $this->actingAs($user)->get(route('sms.export.pdf', ['search' => 'vip']));
        $response->assertOk();
        $this->assertSame('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('.pdf', $response->headers->get('Content-Disposition'));
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }
}
