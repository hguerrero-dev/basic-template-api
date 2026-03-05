<?php

namespace App\Modules\Roles\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Roles\Enums\SystemRole;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin es estríctamente WEB
        $superAdmin = Role::firstOrCreate([
            'name' => SystemRole::SuperAdmin->value,
            'guard_name' => 'web'
        ]);
        $superAdmin->syncPermissions(Permission::where('guard_name', 'web')->get());

        // 2. Admin es estrictamente API (como solicitaste) y tiene todos sus permisos guard=api
        $admin = Role::firstOrCreate([
            'name' => SystemRole::Admin->value,
            'guard_name' => 'api'
        ]);
        $admin->syncPermissions(Permission::where('guard_name', 'api')->get());

        // 3. Customer es estrictamente API también
        Role::firstOrCreate([
            'name' => SystemRole::Customer->value,
            'guard_name' => 'api'
        ]);
        
        // El día de mañana, si creas Soporte Técnico, decides aquí si es web o api
        // Role::firstOrCreate(['name' => 'soporte_tecnico', 'guard_name' => 'web_o_api']);
    }
}
