<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use App\Livewire\Forms\Auth\RegisterForm;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;

class Register extends Component
{
    public RegisterForm $form;

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $this->form->validate();

        $user = $this->form->save();

        Auth::login($user);

        $this->redirect(RouteServiceProvider::HOME, navigate: true);
    }
}
