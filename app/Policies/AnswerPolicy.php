<?php

namespace App\Policies;

use App\Models\MoonshineUser;
use App\Models\Answer;

class AnswerPolicy
{
    public function viewAny(MoonshineUser $user): bool
    {
        return in_array($user->moonshineUserRole?->name, ['Super Admin', 'Администратор опросов']);
    }

    public function view(MoonshineUser $user, Answer $model): bool
    {
        return in_array($user->moonshineUserRole?->name, ['Super Admin', 'Администратор опросов']);
    }

    public function create(MoonshineUser $user): bool
    {
        return in_array($user->moonshineUserRole?->name, ['Super Admin', 'Администратор опросов']);
    }

    public function update(MoonshineUser $user, Answer $model): bool
    {
        return in_array($user->moonshineUserRole?->name, ['Super Admin', 'Администратор опросов']);
    }

    public function delete(MoonshineUser $user, Answer $model): bool
    {
        return in_array($user->moonshineUserRole?->name, ['Super Admin', 'Администратор опросов']);
    }
}
