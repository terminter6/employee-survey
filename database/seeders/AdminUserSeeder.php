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
        // Создаём роль Super Admin, если не существует
        $superAdminRole = MoonshineUserRole::firstOrCreate(
            ['name' => 'Super Admin'],
            ['created_at' => now()]
        );

        // Создаём роль Admin, если не существует
        $adminRole = MoonshineUserRole::firstOrCreate(
            ['name' => 'Admin'],
            ['created_at' => now()]
        );

        // Создаём главного администратора (Super Admin)
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

        // Создаём обычного администратора
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
