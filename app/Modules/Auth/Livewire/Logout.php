<?php

namespace App\Modules\Auth\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class Logout extends Component
{
    public function confirmLogout()
    {
        $this->dispatch('open-confirm-modal', [
            'title' => 'Cerrar Sesión',
            'message' => '¿Estás seguro de que deseas cerrar sesión?',
            'event' => 'logout',
            'params' => null
        ]);
    }

    #[On('logout')]
    public function logout()
    {
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/login');
    }

    public function render()
    {
        return view('auth::logout');
    }
}
