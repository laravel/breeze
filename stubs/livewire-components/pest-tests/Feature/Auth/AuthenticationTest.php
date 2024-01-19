<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Livewire\Livewire;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response
        ->assertOk()
        ->assertSeeLivewire(\App\Livewire\Auth\Login::class);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $component = Livewire::test(\App\Livewire\Auth\Login::class)
        ->set('form.email', $user->email)
        ->set('form.password', 'password');

    $component->call('login');

    $component
        ->assertHasNoErrors()
        ->assertRedirect(RouteServiceProvider::HOME);

    $this->assertAuthenticated();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $component = Livewire::test(\App\Livewire\Auth\Login::class)
        ->set('form.email', $user->email)
        ->set('form.password', 'wrong-password');

    $component->call('login');

    $component
        ->assertHasErrors()
        ->assertNoRedirect();

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Livewire::test(\App\Livewire\Auth\Logout::class);

    $component->call('logout');

    $component
        ->assertHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
});
