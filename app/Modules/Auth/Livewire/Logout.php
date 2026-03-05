<?php

namespace App\Modules\Auth\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class Logout extends Component
{
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
        return <<<'HTML'
        <button 
            x-on:click="$dispatch('open-confirm', { title: 'Cerrar Sesión', message: '¿Estás seguro de que deseas cerrar sesión?', event: 'logout' })"
            class="flex items-center gap-2 text-sm text-gray-600 hover:text-red-600 font-medium transition-colors cursor-pointer">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            Cerrar Sesión
        </button>
        HTML;
    }
}
