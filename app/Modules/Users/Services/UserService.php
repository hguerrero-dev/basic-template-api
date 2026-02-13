<?php

namespace App\Modules\Users\Services;

use App\Modules\Roles\Enums\SystemRole;
use App\Modules\Users\Enums\UserStatus;
use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getAll(?string $search = null, ?int $perPage = null)
    {
        $limit = $perPage ?? config('api.pagination.default');

        return User::with('roles')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($block) use ($search) {
                    $block->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
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
            $user->assignRole(SystemRole::Customer->value); // Asigna un rol vacío para evitar inconsistencias
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
