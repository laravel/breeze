<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
    
    public function test_users_can_register_and_then_authenticate_using_the_login_screen()
    {
        $newUserEmail = $this->faker->unique()->safeEmail;

        $this->post('/register', [
            'email' => $newUserEmail,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->app['auth']->logout();

        $this->assertGuest();

        $user = Person::where('email', $newUserEmail)->first();

        $response = $this->post('/login', [
            'email' => $newUserEmail,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('person.edit', $user));
    }
}
