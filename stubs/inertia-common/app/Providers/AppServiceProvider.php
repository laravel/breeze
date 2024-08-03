<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::authorizationView(function ($params) {
            return Inertia::render('Auth/OAuth/Authorize', [
                'user' => $params['user'],
                'client' => $params['client'],
                'scopes' => $params['scopes'],
                'state' => $params['request']->state,
                'authToken' => $params['authToken'],
                'promptLoginUrl' => $params['request']->fullUrlWithQuery(['prompt' => 'login']),
            ]);
        });
    }
}
