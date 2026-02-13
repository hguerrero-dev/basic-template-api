<?php

namespace App\Modules\Auth\Services;

use App\Modules\Users\Enums\UserStatus;
use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function authenticate(string $identifier, string $password)
    {
        $user = User::where('email', $identifier)
            ->orWhere('username', $identifier)
            ->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['Credenciales inválidas.'],
            ]);
        }

        if ($user->status !== UserStatus::Active) {

            $mensaje = match ($user->status) {
                UserStatus::Banned => 'Tu cuenta ha sido bloqueada permanently.',
                UserStatus::Pending => 'Debes verificar tu correo primero.',
                UserStatus::Inactive => 'Tu cuenta está desactivada.',
                default => 'No tienes acceso.',
            };

            throw ValidationException::withMessages(['username' => [$mensaje]]);
        }

        return [
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => $user,
            'roles' => $user->roles->pluck('name'),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ];
    }
}
