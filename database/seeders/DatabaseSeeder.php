<?php

namespace Database\Seeders;

use App\Modules\Users\Database\Seeders\UserPermissionSeeder;
use Illuminate\Database\Seeder;
use App\Modules\Users\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Primero crear roles y permisos
        $this->call(RolesAndPermissionsSeeder::class);

        $this->call(UserPermissionSeeder::class);

        // 2. Crear un Admin y asignarle el rol
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@mingob.gob.pa',
            'password' => bcrypt('password'), // O Hash::make('password')
        ]);

        $admin->assignRole('admin');

        // 3. Crear usuarios normales de prueba
        User::factory(10)->create()->each(function ($user) {
            $user->assignRole('user');
        });
    }
}
