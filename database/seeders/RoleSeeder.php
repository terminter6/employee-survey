<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MoonshineUser;
use MoonShine\Laravel\Models\MoonshineUserRole;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Создаём роли (только 2: Super Admin и admin)
        $superAdminRole = MoonshineUserRole::firstOrCreate(
            ['name' => 'Super Admin'],
            ['created_at' => now()]
        );

        $adminRole = MoonshineUserRole::firstOrCreate(
            ['name' => 'Admin'],
            ['created_at' => now()]
        );

        // Создаём главного администратора
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

        // Создаём администратора
        MoonshineUser::updateOrCreate(
            ['email' => 'survey@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'moonshine_user_role_id' => $adminRole->id,
                'avatar' => null,
                'created_at' => now(),
            ]
        );

        $this->command->info('Роли и пользователи созданы!');
        $this->command->info('Super Admin: superadmin@admin.com / password');
        $this->command->info('Admin: survey@admin.com / password');
    }
}
