<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if (
            $request->user() &&
            ($request->user() instanceof User) &&
            $request->user()->hasVerifiedEmail()
        ) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        if (
            $request->user() &&
            ($request->user() instanceof User)
        ) {
            $request->user()->sendEmailVerificationNotification();
        }

        return back()->with('status', 'verification-link-sent');
    }
}
