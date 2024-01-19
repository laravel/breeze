<?php

use App\Providers\RouteServiceProvider;
use Livewire\Livewire;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response
        ->assertOk()
        ->assertSeeLivewire(\App\Livewire\Auth\Register::class);
});

test('new users can register', function () {
    $component = Livewire::test(\App\Livewire\Auth\Register::class)
        ->set('form.name', 'Test User')
        ->set('form.email', 'test@example.com')
        ->set('form.password', 'password')
        ->set('form.password_confirmation', 'password');

    $component->call('register');

    $component->assertRedirect(RouteServiceProvider::HOME);

    $this->assertAuthenticated();
});

