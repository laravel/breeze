<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Livewire\Livewire;

test('confirm password screen can be rendered', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/confirm-password');

    $response
        ->assertOk()
        ->assertSeeLivewire(\App\Livewire\Auth\ConfirmPassword::class);
});

test('password can be confirmed', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Livewire::test(\App\Livewire\Auth\ConfirmPassword::class)
        ->set('password', 'password');

    $component->call('confirmPassword');

    $component
        ->assertRedirect(RouteServiceProvider::HOME)
        ->assertHasNoErrors();
});

test('password is not confirmed with invalid password', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Livewire::test(\App\Livewire\Auth\ConfirmPassword::class)
        ->set('password', 'wrong-password');

    $component->call('confirmPassword');

    $component
        ->assertNoRedirect()
        ->assertHasErrors('password');
});
