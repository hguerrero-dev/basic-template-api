<?php

namespace App\Modules\Auth\Livewire;

use App\Modules\Auth\DTOs\RegisterDTO;
use App\Modules\Auth\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Register extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = ''; // Livewire lo detecta con la regla 'confirmed'

    public function registerUser(AuthService $authService)
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = $authService->register(
            $this->name,
            $this->email,
            $this->password
        );

        // Lo autenticamos de inmediato en la web
        Auth::login($user);

        // Y lo mandamos al Dashboard mágico con recarga SPA
        return $this->redirect('/', navigate: true);
    }


    public function render()
    {
        return view('auth::register');
    }
}
