<?php

namespace App\Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Users\Enums\UserPermission;
use Spatie\Permission\Models\Permission;

class UserPermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (UserPermission::cases() as $permission) {
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
