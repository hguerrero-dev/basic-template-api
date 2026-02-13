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
        $superAdmin = Role::firstOrCreate([
            'name' => SystemRole::SuperAdmin->value,
            'guard_name' => 'api'
        ]);

        $superAdmin->syncPermissions(Permission::all());

        Role::firstOrCreate([
            'name' => SystemRole::Admin->value,
            'guard_name' => 'api'
        ]);

        Role::firstOrCreate([
            'name' => SystemRole::Customer->value,
            'guard_name' => 'api'
        ]);
    }
}
