<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MoonshineUser;
use MoonShine\Laravel\Models\MoonshineUserRole;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Получаем роли
        $superAdminRole = MoonshineUserRole::where('name', 'Super Admin')->first();
        $adminRole = MoonshineUserRole::where('name', 'Admin')->first();

        // Создаём пользователей
        $users = [
            [
                'name' => 'Менеджер',
                'email' => 'manager@example.com',
                'password' => Hash::make('password'),
                'moonshine_user_role_id' => $adminRole->id,
            ],
            [
                'name' => 'Оператор',
                'email' => 'operator@example.com',
                'password' => Hash::make('password'),
                'moonshine_user_role_id' => $adminRole->id,
            ],
            [
                'name' => 'HR Менеджер',
                'email' => 'hr@example.com',
                'password' => Hash::make('password'),
                'moonshine_user_role_id' => $adminRole->id,
            ],
        ];

        foreach ($users as $userData) {
            MoonshineUser::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        $this->command->info('Пользователи созданы!');
        $this->command->info('─────────────────────────────────────────');
        $this->command->info('Email                    | Пароль  | Роль');
        $this->command->info('─────────────────────────────────────────');
        $this->command->info('manager@example.com      | password| Admin');
        $this->command->info('operator@example.com     | password| Admin');
        $this->command->info('hr@example.com           | password| Admin');
        $this->command->info('─────────────────────────────────────────');
        $this->command->info('');
        $this->command->warn('Смените пароли после первого входа!');
    }
}
