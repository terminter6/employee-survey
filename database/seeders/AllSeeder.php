<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MoonshineUser;
use MoonShine\Laravel\Models\MoonshineUserRole;
use Illuminate\Support\Facades\Hash;

class AllSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Запуск комплексного сидера...');
        
        $this->command->info('Создание ролей...');
        $superAdminRole = MoonshineUserRole::firstOrCreate(
            ['name' => 'Super Admin'],
            ['created_at' => now()]
        );
        $adminRole = MoonshineUserRole::firstOrCreate(
            ['name' => 'Admin'],
            ['created_at' => now()]
        );
        $this->command->info('Роли созданы!');

        $this->command->info('👥 Создание пользователей...');
        $users = [
            [
                'name' => 'Главный Администратор',
                'email' => 'superadmin@admin.com',
                'password' => Hash::make('password'),
                'moonshine_user_role_id' => $superAdminRole->id,
            ],
            [
                'name' => 'Admin',
                'email' => 'survey@admin.com',
                'password' => Hash::make('password'),
                'moonshine_user_role_id' => $adminRole->id,
            ],
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

        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('Аккаунты для входа:');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('superadmin@admin.com  | password | Super Admin');
        $this->command->info('survey@admin.com      | password | Admin');
        $this->command->info('manager@example.com   | password | Admin');
        $this->command->info('operator@example.com  | password | Admin');
        $this->command->info('hr@example.com        | password | Admin');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->warn('Смените пароли после первого входа!');
        $this->command->info('');
    }
}
