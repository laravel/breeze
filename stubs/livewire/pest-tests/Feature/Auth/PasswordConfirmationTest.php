<?php

use App\Livewire\Auth\ConfirmPassword;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

test('confirm password screen can be rendered', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/confirm-password');

    $response
        ->assertSee('auth.confirm-password')
        ->assertStatus(200);
});

test('password can be confirmed', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(ConfirmPassword::class)
        ->set('password', 'password')
        ->call('confirmPassword')
        ->assertRedirect('/dashboard')
        ->assertHasNoErrors();
});

test('password is not confirmed with invalid password', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(ConfirmPassword::class)
        ->set('password', 'wrong-password')
        ->call('confirmPassword')
        ->assertNoRedirect()
        ->assertHasErrors('password');
});
