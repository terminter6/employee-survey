<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateMoonShineUser extends Command
{
    protected $signature = 'moonshine:create-user';
    protected $description = 'Создать пользователя MoonShine';

    public function handle(): int
    {
        $name = $this->ask('Имя');
        $email = $this->ask('Email');
        $password = $this->secret('Пароль');
        $passwordConfirmation = $this->secret('Подтвердите пароль');

        if ($password !== $passwordConfirmation) {
            $this->error('Пароли не совпадают');
            return self::FAILURE;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info('Пользователь успешно создан!');
        return self::SUCCESS;
    }
}
