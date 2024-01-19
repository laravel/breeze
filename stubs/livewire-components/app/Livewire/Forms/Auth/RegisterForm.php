<?php

namespace App\Livewire\Forms\Auth;

use Livewire\Form;
use App\Models\User;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class RegisterForm extends Form
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Define validation rules
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ];
    }

    /**
     * Save a user from the registration form
     */
    public function save(): User
    {
        $this->password = Hash::make($this->password);

        $user = User::create($this->all());

        event(new Registered($user));

        return $user;
    }
}
