<?php

use App\Livewire\Profile\DeleteUser;
use App\Livewire\Profile\UpdateProfileInformation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;


test('profile page is displayed', function () {
    $this->actingAs($this->user)
        ->get('/profile')
        ->assertOk()
        ->assertSee('profile.update-profile-information')
        ->assertSee('profile.update-password')
        ->assertSee('profile.delete-user');
});

test('profile information can be updated', function () {
    $this->actingAs($this->user);

    Livewire::test(UpdateProfileInformation::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->call('updateProfileInformation')
        ->assertHasNoErrors()
        ->assertNoRedirect();

    $this->user->refresh();

    expect($this->user->name)->toBe('Test User');
    expect($this->user->email)->toBe('test@example.com');
    expect($this->user->email_verified_at)->toBeNull();
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $this->actingAs($this->user);

    Livewire::test(UpdateProfileInformation::class)
        ->set('name', 'Test User')
        ->set('email', $this->user->email)
        ->call('updateProfileInformation')
        ->assertHasNoErrors()
        ->assertNoRedirect();

    expect($this->user->refresh()->email_verified_at)->not->toBeNull();
});

test('user can delete their account', function () {
    $this->actingAs($this->user);

    Livewire::test(DeleteUser::class)
        ->set('password', 'password')
        ->call('deleteUser')
        ->assertHasNoErrors()
        ->assertRedirect('/');

    expect(auth()->user())->toBeNull();
});

test('correct password must be provided to delete account', function () {
    $this->actingAs($this->user);

    Livewire::test(DeleteUser::class)
        ->set('password', 'wrong-password')
        ->call('deleteUser')
        ->assertHasErrors('password')
        ->assertNoRedirect();

    expect($this->user->fresh())->not->toBeNull();
});
