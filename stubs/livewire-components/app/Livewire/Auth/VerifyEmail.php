<?php

namespace App\Livewire\Auth;

use App\Livewire\Actions\Logout;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VerifyEmail extends Component
{
    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.auth.verify-email');
    }

    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirect(
                session('url.intended', RouteServiceProvider::HOME),
                navigate: true
            );

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        session()->flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout)
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}
