<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Livewire\Forms\Auth\ResetPasswordForm;
use Illuminate\Support\Facades\Password;

class ResetPassword extends Component
{
    public ResetPasswordForm $form;
    
    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.auth.reset-password');
    }

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->form->token = $token;
        $this->form->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->form->validate();

        $status = $this->form->resetPassword();

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PASSWORD_RESET) {
            $this->addError('form.email', __($status));

            return;
        }

        session()->flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}
