<?php

namespace Tests\Browser\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Laravel\Dusk\Browser;
use ProtoneMedia\LaravelDuskFakes\Notifications\PersistentNotifications;
use Tests\DuskTestCase;

class PasswordResetTest extends DuskTestCase
{
    use DatabaseMigrations;
    use PersistentNotifications;

    public function test_reset_password_link_screen_can_be_rendered()
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                ->visit('/forgot-password')
                ->assertInputPresent('email');
        });
    }

    public function test_reset_password_link_can_be_requested()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();

            $browser->logout()
                ->visit('/forgot-password')
                ->type('email', $user->email)
                ->press('Email Password Reset Link')
                ->waitForText('We have emailed your password reset link!');

            Notification::assertSentTo($user, ResetPassword::class);
        });
    }

    public function test_reset_password_screen_can_be_rendered()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();

            $browser->logout()
                ->visit('/forgot-password')
                ->type('email', $user->email)
                ->press('Email Password Reset Link')
                ->waitForText('We have emailed your password reset link!');

            Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
                $this->browse(function (Browser $browser) use ($notification, $user) {
                    $browser->visit('/reset-password/'.$notification->token.'?email='.$user->email)
                        ->assertInputValue('email', $user->email);
                });

                return true;
            });
        });
    }

    public function test_password_can_be_reset_with_valid_token()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();

            $browser->logout()
                ->visit('/forgot-password')
                ->type('email', $user->email)
                ->press('Email Password Reset Link')
                ->waitForText('We have emailed your password reset link!');

            Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
                $this->browse(function (Browser $browser) use ($notification, $user) {
                    $browser->visit('/reset-password/'.$notification->token.'?email='.$user->email)
                        ->assertInputValue('email', $user->email)
                        ->type('password', 'password')
                        ->type('password_confirmation', 'password')
                        ->press('Reset Password')
                        ->waitForText('Your password has been reset!')
                        ->assertPathIs('/login');
                });

                return true;
            });
        });
    }
}
