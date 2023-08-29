<?php

namespace App\Http\Controllers\Auth;

use App\{
    Http\Controllers\Controller,
    Models\User,
    Providers\RouteServiceProvider
};
use Illuminate\{
    Auth\Events\Registered,
    Http\RedirectResponse,
    Http\Request,
    Support\Facades\Auth,
    Support\Facades\Hash,
    Validation\Rules
};
use Inertia\{
    Inertia,
    Response
};

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
