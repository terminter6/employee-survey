<?php

namespace App\Policies;

use App\Models\MoonshineUser;
use App\Models\EmployeeEmail;

class EmployeeEmailPolicy
{
    public function viewAny(MoonshineUser $user): bool
    {
        return $user->moonshineUserRole?->name === 'Super Admin';
    }

    public function view(MoonshineUser $user, EmployeeEmail $model): bool
    {
        return $user->moonshineUserRole?->name === 'Super Admin';
    }

    public function create(MoonshineUser $user): bool
    {
        return $user->moonshineUserRole?->name === 'Super Admin';
    }

    public function update(MoonshineUser $user, EmployeeEmail $model): bool
    {
        return $user->moonshineUserRole?->name === 'Super Admin';
    }

    public function delete(MoonshineUser $user, EmployeeEmail $model): bool
    {
        return $user->moonshineUserRole?->name === 'Super Admin';
    }
}
