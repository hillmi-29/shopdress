<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRequiredTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_homepage_offers_a_login_dialog_instead_of_a_forced_redirect(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('login-required', false)
            ->assertSee('Masuk ke akun')
            ->assertSee("Alpine.data('authRequiredDialog'", false);
    }

    public function test_login_returns_to_the_page_that_asked_for_authentication(): void
    {
        $user = User::factory()->create();

        $this->get('/masuk?redirect=/produk/dress-contoh')->assertOk();

        $this->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(url('/produk/dress-contoh'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_ignores_external_redirect_targets(): void
    {
        $user = User::factory()->create();

        $this->get('/masuk?redirect=//evil.test/phish')->assertOk();

        $this->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_register_page_keeps_the_redirect_target_when_switching_to_login(): void
    {
        $this->get('/daftar?redirect=/keranjang')
            ->assertOk()
            ->assertSee('/masuk?redirect=%2Fkeranjang', false);

        $this->get('/masuk?redirect=/keranjang')
            ->assertOk()
            ->assertSee('/daftar?redirect=%2Fkeranjang', false);
    }
}
