<?php

namespace App\Modules\Roles\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Roles\Enums\RolePermission;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (RolePermission::cases() as $permission) {
            Permission::firstOrCreate([
                'name' => $permission->value,
                'guard_name' => 'api'
            ]);
        }
    }
}
