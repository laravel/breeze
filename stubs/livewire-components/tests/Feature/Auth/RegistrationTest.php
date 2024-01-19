<?php

namespace Tests\Feature\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response
            ->assertOk()
            ->assertSeeLivewire(\App\Livewire\Auth\Register::class);
    }

    public function test_new_users_can_register(): void
    {
        $component = Livewire::test(\App\Livewire\Auth\Register::class)
            ->set('form.name', 'Test User')
            ->set('form.email', 'test@example.com')
            ->set('form.password', 'password')
            ->set('form.password_confirmation', 'password');

        $component->call('register');

        $component->assertRedirect(RouteServiceProvider::HOME);

        $this->assertAuthenticated();
    }
}
