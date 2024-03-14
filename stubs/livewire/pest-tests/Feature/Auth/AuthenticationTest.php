<?php

use App\Livewire\Auth\Login;
use App\Livewire\Layout\Navigation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

beforeEach(function () {
    $this->refreshDatabase();
});

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response
        ->assertOk()
        ->assertSee('auth.login');
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $component = Livewire::test(Login::class)
        ->set('form.email', $user->email)
        ->set('form.password', 'password');

    $component->call('login');

    $component
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});


test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $component = Livewire::test(Login::class)
        ->set('form.email', $user->email)
        ->set('form.password', 'wrong-password');

    $component->call('login');

    $component
        ->assertHasErrors()
        ->assertNoRedirect();

    $this->assertGuest();
});

test('navigation menu can be rendered', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get('/dashboard');

    $response
        ->assertOk()
        ->assertSee('layout.navigation');
});

test('users can logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Livewire::test(Navigation::class);

    $component->call('logout');

    $component
        ->assertHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
});
