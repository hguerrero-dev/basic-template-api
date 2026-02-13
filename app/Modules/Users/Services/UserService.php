<?php

namespace App\Modules\Users\Services;

use App\Modules\Users\Enums\UserStatus;
use App\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getAll(): Collection
    {
        return User::with('roles')->get();
    }

    public function getByOne($id)
    {
        return User::with('roles')->findOrFail($id);
    }

    public function create($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'] ?? null,
            'password' => $this->hashPassword($data['password']),
            'status' => $data['status'] ?? UserStatus::Active,
        ]);

        $this->manageRoles($user, $data['roles'] ?? []);

        return $user;
    }

    public function update($id, $data)
    {
        $user = User::findOrFail($id);

        $user->update([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'] ?? null,
            'status' => $data['status'] ?? UserStatus::Active,
        ]);

        if (isset($data['password'])) {
            $user->update([
                'password' => $this->hashPassword($data['password']),
            ]);
        }

        $this->manageRoles($user, $data['roles'] ?? []);

        return $user;
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    }

    protected function manageRoles(User $user, array $roles)
    {
        if ($roles) {
            $user->syncRoles($roles);
        } elseif ($user->roles()->count() === 0) {
            $user->assignRole('user');
        }
    }

    protected function hashPassword(string $password): string
    {
        if (Hash::needsRehash($password)) {
            return Hash::make($password);
        }

        return $password;
    }
}
