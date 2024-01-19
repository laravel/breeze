<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Livewire\Actions\Logout as ActionLogout;

class Logout extends Component
{
    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.auth.logout');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(ActionLogout $logout)
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}
