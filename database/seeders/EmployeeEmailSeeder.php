<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmployeeEmail;

class EmployeeEmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            ['name' => 'Иванов Иван', 'email' => 'ivanov@example.com'],
            ['name' => 'Петров Пётр', 'email' => 'petrov@example.com'],
            ['name' => 'Сидоров Сидор', 'email' => 'sidorov@example.com'],
            ['name' => 'Смирнова Анна', 'email' => 'smirnova@example.com'],
            ['name' => 'Козлова Мария', 'email' => 'kozlova@example.com'],
        ];

        foreach ($employees as $employee) {
            EmployeeEmail::firstOrCreate(
                ['email' => $employee['email']],
                ['name' => $employee['name']]
            );
        }
    }
}
