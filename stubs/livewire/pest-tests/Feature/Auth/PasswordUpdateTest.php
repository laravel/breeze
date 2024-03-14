<?php

use App\Livewire\Profile\UpdatePassword;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;


test('password can be updated', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(UpdatePassword::class)
        ->set('current_password', 'password')
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('updatePassword')
        ->assertHasNoErrors()
        ->assertNoRedirect();

    expect(Hash::check('new-password', $user->refresh()->password))->toBeTrue();
});

test('correct password must be provided to update password', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(UpdatePassword::class)
        ->set('current_password', 'wrong-password')
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('updatePassword')
        ->assertHasErrors(['current_password'])
        ->assertNoRedirect();
});
