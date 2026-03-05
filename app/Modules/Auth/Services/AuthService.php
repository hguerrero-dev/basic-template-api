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

        return [
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => $user,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
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
        $username = $this->generateUniqueUsername($email);

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'username' => $username,
            'password' => Hash::make($password),
            'status' => UserStatus::Active, // change to Pending if you want email verification
        ]);

        return $user;
    }

    private function generateUniqueUsername(string $email): string
    {
        $baseUsername = strtolower(explode('@', $email)[0]);
        $baseUsername = preg_replace('/[^a-z0-9]/', '', $baseUsername);

        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
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
