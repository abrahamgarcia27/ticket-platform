<?php

namespace Tests\Feature;

use App\Models\BankAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * These tests use RefreshDatabase, so they only run against an in-memory
 * SQLite database to never wipe the local MySQL database:
 *
 *   DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --filter=UserBankAccountTest
 */
class UserBankAccountTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        if (getenv('DB_CONNECTION') !== 'sqlite' || getenv('DB_DATABASE') !== ':memory:') {
            $this->markTestSkipped('Run with DB_CONNECTION=sqlite DB_DATABASE=:memory: to avoid touching the local database.');
        }

        parent::setUp();
    }

    private function makeUser(): User
    {
        return User::create([
            'username' => 'tester',
            'email' => 'tester@example.com',
            'password' => 'secret123',
        ]);
    }

    public function test_guest_cannot_update_bank_account(): void
    {
        $this->post(route('profile.bank-account.update'), [])->assertRedirect(route('login'));
    }

    public function test_required_fields_are_validated(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)
            ->from(route('profile'))
            ->post(route('profile.bank-account.update'), [])
            ->assertRedirect(route('profile'))
            ->assertSessionHasErrors(['bank_name', 'account_holder', 'account_number']);

        $this->assertSame(0, BankAccount::count());
    }

    public function test_account_number_must_be_digits_with_valid_length(): void
    {
        $user = $this->makeUser();

        foreach (['12345', '1234567890123', 'ABCDEFGHIJ', '12345678901234567X'] as $invalid) {
            $this->actingAs($user)
                ->from(route('profile'))
                ->post(route('profile.bank-account.update'), [
                    'bank_name' => 'BBVA',
                    'account_holder' => 'Tester',
                    'account_number' => $invalid,
                ])
                ->assertSessionHasErrors('account_number');
        }

        $this->assertSame(0, BankAccount::count());
    }

    public function test_user_can_create_and_update_bank_account(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)
            ->post(route('profile.bank-account.update'), [
                'bank_name' => 'BBVA',
                'account_holder' => 'Tester One',
                'account_number' => '0121 8000 1234 5678 91',
            ])
            ->assertRedirect(route('profile'))
            ->assertSessionHasNoErrors()
            ->assertSessionHas('succes');

        $account = $user->fresh()->bankAccount;
        $this->assertSame('BBVA', $account->bank_name);
        $this->assertSame('012180001234567891', $account->account_number);

        // Stored encrypted, not in plain text.
        $raw = DB::table('bank_accounts')->where('id', $account->id)->value('account_number');
        $this->assertNotSame('012180001234567891', $raw);

        $this->actingAs($user)
            ->post(route('profile.bank-account.update'), [
                'bank_name' => 'Banorte',
                'account_holder' => 'Tester Two',
                'account_number' => '1234567890',
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame(1, BankAccount::count());
        $account = $user->fresh()->bankAccount;
        $this->assertSame('Banorte', $account->bank_name);
        $this->assertSame('Tester Two', $account->account_holder);
        $this->assertSame('1234567890', $account->account_number);
    }

    public function test_profile_prefills_saved_bank_account(): void
    {
        $user = $this->makeUser();
        $user->bankAccount()->create([
            'bank_name' => 'Santander',
            'account_holder' => 'Tester Prefill',
            'account_number' => '014180655012345678',
        ]);

        $this->actingAs($user)
            ->get(route('profile'))
            ->assertOk()
            ->assertSee('Bank Account')
            ->assertSee('value="Santander"', false)
            ->assertSee('value="Tester Prefill"', false)
            ->assertSee('014180655012345678');
    }
}
