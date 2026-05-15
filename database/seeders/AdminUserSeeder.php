<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use MoonShine\Laravel\Models\MoonshineUser;
use MoonShine\Laravel\Models\MoonshineUserRole;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = MoonshineUserRole::firstOrCreate(
            ['name' => 'Super Admin'],
            ['created_at' => now()]
        );

        $adminRole = MoonshineUserRole::firstOrCreate(
            ['name' => 'Admin'],
            ['created_at' => now()]
        );

        MoonshineUser::updateOrCreate(
            ['email' => 'superadmin@admin.com'],
            [
                'name' => 'Главный Администратор',
                'password' => Hash::make('password'),
                'moonshine_user_role_id' => $superAdminRole->id,
                'avatar' => null,
                'created_at' => now(),
            ]
        );

        MoonshineUser::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Администратор',
                'password' => Hash::make('password'),
                'moonshine_user_role_id' => $adminRole->id,
                'avatar' => null,
                'created_at' => now(),
            ]
        );

        $this->command->info('Пользователи созданы/обновлены!');
        $this->command->info('Super Admin: superadmin@admin.com / password');
        $this->command->info('Admin: admin@admin.com / password');
    }
}
