<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

test('password can be updated', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Livewire::test(\App\Livewire\Profile\UpdatePassword::class)
        ->set('current_password', 'password')
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('updatePassword');

    $component
        ->assertHasNoErrors()
        ->assertNoRedirect();

    $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
});

test('correct password must be provided to update password', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Livewire::test(\App\Livewire\Profile\UpdatePassword::class)
        ->set('current_password', 'wrong-password')
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('updatePassword');

    $component
        ->assertHasErrors(['current_password'])
        ->assertNoRedirect();
});
