<?php

use App\Models\User;
use Livewire\Volt\Volt;

test('profile page is displayed', function () {
    $this->actingAs(
        $user = User::factory()->create(),
    );

    $response = $this->get('/profile');

    $response
        ->assertSeeVolt('profile.update-profile-information-form')
        ->assertSeeVolt('profile.update-password-form')
        ->assertSeeVolt('profile.delete-user-form')
        ->assertOk();
});

test('profile information can be updated', function () {
    $this->actingAs(
        $user = User::factory()->create(),
    );

    $component = Volt::test('profile.update-profile-information-form')
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->call('updateProfileInformation');

    $user->refresh();

    $component
        ->assertHasNoErrors()
        ->assertNoRedirect();

    $this->assertSame('Test User', $user->name);
    $this->assertSame('test@example.com', $user->email);
    $this->assertNull($user->email_verified_at);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $this->actingAs(
        $user = User::factory()->create(),
    );

    $component = Volt::test('profile.update-profile-information-form')
        ->set('name', 'Test User')
        ->set('email', $user->email)
        ->call('updateProfileInformation');

    $component
        ->assertHasNoErrors()
        ->assertNoRedirect();

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('user can delete their account', function () {
    $this->actingAs(
        $user = User::factory()->create(),
    );

    $component = Volt::test('profile.delete-user-form')
        ->set('password', 'password')
        ->call('deleteUser');

    $component
        ->assertHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $this->actingAs(
        $user = User::factory()->create(),
    );

    $component = Volt::test('profile.delete-user-form')
        ->set('password', 'wrong-password')
        ->call('deleteUser');

    $component
        ->assertHasErrors('password')
        ->assertNoRedirect();

    $this->assertNotNull($user->fresh());
});
