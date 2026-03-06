<?php

namespace App\Modules\Audit\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Modules\Audit\Enums\AuditPermission;

class AuditPermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (AuditPermission::cases() as $permission) {
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
