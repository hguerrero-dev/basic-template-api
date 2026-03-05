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
        $guards = ['web', 'api'];

        foreach ($guards as $guard) {
            $superAdmin = Role::firstOrCreate([
                'name' => SystemRole::SuperAdmin->value,
                'guard_name' => $guard
            ]);

            $superAdmin->syncPermissions(Permission::where('guard_name', $guard)->get());

            Role::firstOrCreate([
                'name' => SystemRole::Admin->value,
                'guard_name' => $guard
            ]);

            Role::firstOrCreate([
                'name' => SystemRole::Customer->value,
                'guard_name' => $guard
            ]);
        }
    }
}
