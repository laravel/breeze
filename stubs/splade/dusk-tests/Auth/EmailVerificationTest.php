<?php

namespace Tests\Browser\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\URL;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EmailVerificationTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_email_verification_screen_can_be_rendered()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create([
                'email_verified_at' => null,
            ]);

            $browser->loginAs($user)
                ->visit('/verify-email')
                ->waitForText('Thanks for signing up!')
                ->assertSeeIn('button', 'Resend Verification Email');
        });
    }

    public function test_email_can_be_verified()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create([
                'email_verified_at' => null,
            ]);

            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id' => $user->id, 'hash' => sha1($user->email)]
            );

            $browser->loginAs($user)
                ->visit($verificationUrl)
                ->waitForText('You\'re logged in!')
                ->assertPathIs(RouteServiceProvider::HOME)
                ->assertQueryStringHas('verified', 1);

            $this->assertTrue($user->fresh()->hasVerifiedEmail());
        });
    }

    public function test_email_is_not_verified_with_invalid_hash()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create([
                'email_verified_at' => null,
            ]);

            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id' => $user->id, 'hash' => sha1('wrong-email')]
            );

            $browser->loginAs($user)
                ->visit($verificationUrl)
                ->assertSee('403');

            $this->assertFalse($user->fresh()->hasVerifiedEmail());
        });
    }
}
