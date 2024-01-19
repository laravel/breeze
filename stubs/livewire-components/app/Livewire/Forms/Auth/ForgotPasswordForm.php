<?php

namespace App\Livewire\Forms\Auth;

use Livewire\Form;
use Illuminate\Support\Facades\Password;

class ForgotPasswordForm extends Form
{
    public string $email = '';

    /**
     * Define validation rules
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email']
        ];
    }

    /**
     * Send a password reset link to the provided email address.
     */
    public function send(): string
    {
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        return $status;
    }
}
