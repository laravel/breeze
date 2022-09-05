<?php

namespace Tests\Browser\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PasswordConfirmationTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_confirm_password_screen_can_be_rendered()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();

            $browser->loginAs($user)
                ->visit('/confirm-password')
                ->assertInputPresent('password');
        });
    }

    public function test_password_can_be_confirmed()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();

            $browser->loginAs($user)
                ->visit('/confirm-password')
                ->type('password', 'password')
                ->press('Confirm')
                ->waitForLocation(RouteServiceProvider::HOME)
                ->assertPathIs(RouteServiceProvider::HOME);
        });
    }

    public function test_password_is_not_confirmed_with_invalid_password()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();

            $browser->loginAs($user)
                ->visit('/confirm-password')
                ->type('password', 'wrong-password')
                ->press('Confirm')
                ->waitForText('The provided password is incorrect.')
                ->assertPathIs('/confirm-password');
        });
    }
}
