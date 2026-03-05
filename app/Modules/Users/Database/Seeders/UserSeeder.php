<?php

namespace App\Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Modules\Users\Models\User;
use App\Modules\Roles\Enums\SystemRole;
use App\Modules\Users\Enums\UserStatus;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL', 'admin@localhost.com');
        $adminUsername = env('ADMIN_USERNAME', 'admin');

        $admin = User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => env('ADMIN_NAME', 'Super Admin'),
                'username' => $adminUsername,
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
                'status' => UserStatus::Active,
                'email_verified_at' => now(),
            ]
        );

        // Se le asigna el único que es WEB
        $superAdminWeb = Role::findByName(SystemRole::SuperAdmin->value, 'web');
        $admin->assignRole($superAdminWeb);

        // (Optional) create some regular users for testing
        if (app()->environment('local')) {
            $customerApi = Role::findByName(SystemRole::Customer->value, 'api');
            
            User::factory(10)->create()->each(function ($user) use ($customerApi) {
                $user->assignRole($customerApi);
            });
        }
    }
}
