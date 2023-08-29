<?php

namespace App\Http\Controllers\Auth;

use App\{
    Http\Controllers\Controller,
    Http\Requests\Auth\LoginRequest,
    Providers\RouteServiceProvider
};
use Illuminate\{
    Http\RedirectResponse,
    Http\Request,
    Support\Facades\Auth,
    Support\Facades\Route
};
use Inertia\{
    Inertia,
    Response
};

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
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
