<?php

namespace App\Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Users\Enums\UserPermissions;
use Spatie\Permission\Models\Permission;

class UserPermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (UserPermissions::cases() as $permission) {
            Permission::firstOrCreate(['name' => $permission->value]);
        }
    }
}
