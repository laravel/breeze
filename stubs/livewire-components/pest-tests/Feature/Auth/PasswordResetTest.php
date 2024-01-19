<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

test('reset password link screen can be rendered', function () {
    $response = $this->get('/forgot-password');

    $response
        ->assertOk()
        ->assertSeeLivewire(\App\Livewire\Auth\ForgotPassword::class);
});

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(\App\Livewire\Auth\ForgotPassword::class)
        ->set('form.email', $user->email)
        ->call('sendPasswordResetLink');

    Notification::assertSentTo($user, ResetPassword::class);
});

test('reset password screen can be rendered', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(\App\Livewire\Auth\ForgotPassword::class)
        ->set('form.email', $user->email)
        ->call('sendPasswordResetLink');

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
        $response = $this->get('/reset-password/'.$notification->token);

        $response->assertStatus(200);

        return true;
    });
});

test('password can be reset with valid token', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(\App\Livewire\Auth\ForgotPassword::class)
        ->set('form.email', $user->email)
        ->call('sendPasswordResetLink');

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        $component = Livewire::test(\App\Livewire\Auth\ResetPassword::class, ['token' => $notification->token])
            ->set('form.email', $user->email)
            ->set('form.password', 'password')
            ->set('form.password_confirmation', 'password');

        $component->call('resetPassword');

        $component
            ->assertRedirect('/login')
            ->assertHasNoErrors();

        return true;
    });
});
