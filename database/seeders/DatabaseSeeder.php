<?php

namespace Database\Seeders;

use App\Modules\Audit\Database\Seeders\AuditPermissionSeeder;
use App\Modules\Roles\Database\Seeders\RolePermissionSeeder;
use App\Modules\Roles\Database\Seeders\RoleSeeder;
use App\Modules\Users\Database\Seeders\UserPermissionSeeder;
use App\Modules\Users\Database\Seeders\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserPermissionSeeder::class,
            RolePermissionSeeder::class,
            AuditPermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
        ]);
    }
}
