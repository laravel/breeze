<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit( Request $request ): View
    {
        return view( 'profile.edit', [
            'user' => $request->user(),
        ] );
    }

    /**
     * Delete the user's account.
     */
    public function destroy( Request $request ): RedirectResponse
    {
        $request->validateWithBag( 'userDeletion', [
            'password' => [ 'required', 'current_password' ],
        ] );

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to( '/' );
    }
}
