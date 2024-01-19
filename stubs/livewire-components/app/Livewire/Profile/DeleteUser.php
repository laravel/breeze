<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use App\Livewire\Actions\Logout;
use Livewire\Component;

class DeleteUser extends Component
{
    public string $password = '';

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.profile.delete-user');
    }

    /**
     * Define validation rules
     */
    public function rules()
    {
        return [
            'password' => ['required', 'string', 'current_password'],
        ];
    }

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate();

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}
