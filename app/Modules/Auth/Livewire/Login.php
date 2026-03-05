<?php

namespace App\Modules\Auth\Livewire;

use Livewire\Component;
use App\Modules\Auth\Services\AuthService;
use Illuminate\Validation\ValidationException;

class Login extends Component
{
    public $identifier = '';
    public $password = '';

    public function authenticate(AuthService $authService)
    {
        $this->validate([
            'identifier' => 'required',
            'password' => 'required'
        ]);

        try {
            $authService->authenticateWeb($this->identifier, $this->password);
            $this->redirect('/', navigate: true);
        } catch (ValidationException $e) {
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
