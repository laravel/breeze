<?php

namespace Tests\Browser\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegistrationTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_registration_screen_can_be_rendered()
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                ->visit('/register')
                ->waitForInput('name')
                ->assertInputPresent('name')
                ->assertInputPresent('email')
                ->assertInputPresent('password')
                ->assertInputPresent('password_confirmation');
        });
    }

    public function test_new_users_can_register()
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                ->visit('/register')
                ->waitForInput('name')
                ->type('name', 'Test User')
                ->type('email', 'test@example.com')
                ->type('password', 'password')
                ->type('password_confirmation', 'password')
                ->press('Register')
                ->waitForLocation(RouteServiceProvider::HOME)
                ->assertAuthenticated();
        });
    }
}
