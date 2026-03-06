<?php

namespace App\Modules\Auth\Services;

use App\Modules\Users\Enums\UserStatus;
use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function authenticate(string $identifier, string $password)
    {
        $user = $this->validateUserCredentials($identifier, $password);

        $roles = $user->getRoleNames()->filter(function ($role) {
            return str_starts_with($role, 'api_');
        });

        $permissions = $user->getAllPermissions()
            ->pluck('name')
            ->filter(function ($permission) {
                return str_starts_with($permission, 'api_');
            });

        return [
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => $user,
            'roles' => $roles->values(),
            'permissions' => $permissions->values(),
        ];
    }

    public function authenticateWeb(string $identifier, string $password)
    {
        $user = $this->validateUserCredentials($identifier, $password);

        Auth::guard('web')->login($user);
        session()->regenerate();

        return $user;
    }

    public function register(string $name, string $email, string $password)
    {
        $username = app(\App\Modules\Users\Services\UserService::class)->generateUniqueUsername($name, $email);

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'username' => $username,
            'password' => Hash::make($password),
            'status' => UserStatus::Active,
        ]);

        return $user;
    }

    private function validateUserCredentials(string $identifier, string $password): User
    {
        $user = User::where('email', $identifier)
            ->orWhere('username', $identifier)
            ->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'identifier' => ['Credenciales inválidas.'],
            ]);
        }

        if ($user->status !== UserStatus::Active) {
            $mensaje = match ($user->status) {
                UserStatus::Banned => 'Tu cuenta ha sido bloqueada permanentemente.',
                UserStatus::Pending => 'Debes verificar tu correo electrónico primero.',
                UserStatus::Inactive => 'Tu cuenta está desactivada.',
                default => 'No tienes acceso.',
            };

            throw ValidationException::withMessages(['identifier' => [$mensaje]]);
        }

        return $user;
    }

    public function logout(User $user)
    {
        $user->tokens()->delete();
    }
}
