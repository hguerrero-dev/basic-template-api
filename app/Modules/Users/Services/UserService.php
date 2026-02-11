<?php

namespace App\Modules\Users\Services;

use App\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getAll(): Collection
    {
        return User::with('roles')->get();
    }

    public function get($id)
    {
        // Logic to retrieve a user
    }

    public function create($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'] ?? null,
            'password' => Hash::make($data['password']),
        ]);

        if (isset($data['roles'])) {
            $user->assignRole($data['roles']);
        } else {
            $user->assignRole('user');
        }

        return $user;
    }

    public function update($id, $data)
    {
        // Logic to update a user
    }

    public function delete($id)
    {
        // Logic to delete a user
    }
}
