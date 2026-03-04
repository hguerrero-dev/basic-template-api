<?php

namespace App\Modules\Auth\Livewire;

use Livewire\Component;
use App\Modules\Auth\Services\AuthService;
use Illuminate\Validation\ValidationException;

class Login extends Component
{
    public $email = '';
    public $password = '';

    public function authenticate(AuthService $authService)
    {
        $this->validate([
            'email' => 'required', // Quité la validación regex de email por si usa un username
            'password' => 'required'
        ]);

        try {
            // Llama a nuestro servicio modular avanzado (soporta banned, Inactive y login por email/usuario)
            $authService->authenticateWeb($this->email, $this->password);
            
            // Si funciona, redirigimos (ajusta a /dashboard o /home)
            $this->redirect('/', navigate: true);

        } catch (ValidationException $e) {
            // Pasamos los errores del AuthService al Frontend de Livewire
            foreach ($e->errors() as $key => $messages) {
                $this->addError($key, $messages[0]);
            }
        }
    }

    public function render()
    {
        return view('auth::login');
    }
}
