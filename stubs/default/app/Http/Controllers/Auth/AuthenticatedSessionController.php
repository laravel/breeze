<?php

namespace App\Http\Controllers\Auth;

use App\{
    Http\Controllers\Controller,
    Http\Requests\Auth\LoginRequest,
    Providers\RouteServiceProvider
};
use Illuminate\Http\{
    RedirectResponse,
    Request
};
use Illuminate\{
    Support\Facades\Auth,
    View\View
};

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
