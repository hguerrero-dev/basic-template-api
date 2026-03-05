<?php

namespace App\Modules\Roles\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Roles\Enums\RolePermission;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Creamos los permisos tanto para web como para api.
        // Web servirá para el super_admin.
        // Api servirá para admin, customer, u otros roles futuros.
        foreach (RolePermission::cases() as $permission) {
            Permission::firstOrCreate([
                'name' => $permission->value,
                'guard_name' => 'web'
            ]);
            
            Permission::firstOrCreate([
                'name' => $permission->value,
                'guard_name' => 'api'
            ]);
        }
    }
}
