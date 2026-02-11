<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Resetear caché
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Crear Permisos para API (IMPORTANTE: guard_name)
        Permission::create(['name' => 'ver usuarios', 'guard_name' => 'api']);
        Permission::create(['name' => 'crear usuarios', 'guard_name' => 'api']);
        Permission::create(['name' => 'editar usuarios', 'guard_name' => 'api']);
        Permission::create(['name' => 'eliminar usuarios', 'guard_name' => 'api']);

        // 3. Crear Roles para API (IMPORTANTE: guard_name también aquí)

        // Rol User
        $roleUser = Role::create(['name' => 'user', 'guard_name' => 'api']);
        $roleUser->givePermissionTo('ver usuarios');

        // Rol Admin
        $roleAdmin = Role::create(['name' => 'admin', 'guard_name' => 'api']);
        // Buscamos los permisos que sean del guard 'api' para asignarlos
        $permissions = Permission::where('guard_name', 'api')->get();
        $roleAdmin->givePermissionTo($permissions);
    }
}
