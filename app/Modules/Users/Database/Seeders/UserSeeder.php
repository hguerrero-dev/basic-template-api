<?php

namespace App\Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Modules\Users\Models\User;
use App\Modules\Roles\Enums\SystemRole;
use App\Modules\Users\Enums\UserStatus;

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

        $admin->syncRoles(SystemRole::SuperAdmin->value);

        // (Optional) create some regular users for testing
        if (app()->environment('local')) {
            User::factory(10)->create()->each(function ($user) {
                $user->assignRole(SystemRole::Customer->value);
            });
        }
    }
}
