<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        if($request->user()->hasVerifiedEmail())
        {
            return redirect()->intended(RouteServiceProvider::HOME); 
        }

        $request->user()->sendEmailVerificationNotification();
        return view('auth.verify-email');
    }
}
