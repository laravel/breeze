<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($user !== null && $user->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
        }

        if ($user !== null && $user->markEmailAsVerified()) {
            /**
             * The next line shows a PHPStan error:
             * "Verified expects MustVerifyEmail, User given."
             * This error is being ignored, but it will also go away when extending the User class with the MustVerifyEmail interface,
             * which you can to do implement mandatory E-Mail verification.
             */
            /** @phpstan-ignore-next-line */
            event(new Verified($user));
        }

        return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    }
}
