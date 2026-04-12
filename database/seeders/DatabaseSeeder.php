<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Создаём админов и супер-админа
        $this->call([
            AdminUserSeeder::class,
        ]);

        // Очищаем и создаём тестовые данные
        $this->call([
            DataSeeder::class,
        ]);
    }
}
