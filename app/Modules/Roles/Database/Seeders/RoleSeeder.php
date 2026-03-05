<?php

namespace App\Modules\Roles\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Roles\Enums\SystemRole;
use App\Modules\Roles\Enums\RolePermission;
use App\Modules\Users\Enums\UserPermission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::firstOrCreate([
            'name' => SystemRole::SuperAdmin->value,
            'guard_name' => 'web'
        ]);
        $superAdmin->syncPermissions(Permission::where('guard_name', 'web')->get());

        $admin = Role::firstOrCreate([
            'name' => SystemRole::Admin->value,
            'guard_name' => 'api'
        ]);
        $admin->syncPermissions(Permission::where('guard_name', 'api')->get());

        Role::firstOrCreate([
            'name' => SystemRole::Customer->value,
            'guard_name' => 'api'
        ]);

        $userRole = Role::firstOrCreate([
            'name' => SystemRole::User->value,
            'guard_name' => 'web'
        ]);

        // Asignamos solo permisos de lectura al rol User
        $userRole->syncPermissions([
            Permission::where('name', UserPermission::View->value)->where('guard_name', 'web')->first(),
            Permission::where('name', RolePermission::View->value)->where('guard_name', 'web')->first(),
        ]);

        // El día de mañana, si creas Soporte Técnico, decides aquí si es web o api
        // Role::firstOrCreate(['name' => 'soporte_tecnico', 'guard_name' => 'web_o_api']);
    }
}
