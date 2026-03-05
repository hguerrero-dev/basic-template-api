<?php

namespace App\Modules\Auth\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Logout extends Component
{
    public function logout()
    {
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/login');
    }

    public function render()
    {
        return <<<'HTML'
        <button wire:click="logout" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
            Cerrar Sesión
        </button>
        HTML;
    }
}
