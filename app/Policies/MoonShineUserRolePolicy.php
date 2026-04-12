<?php

namespace App\Policies;

use App\Models\MoonshineUser;
use MoonShine\Laravel\Models\MoonshineUserRole;

class MoonShineUserRolePolicy
{
    public function viewAny(MoonshineUser $user): bool
    {
        return $user->moonshineUserRole?->name === 'Super Admin';
    }

    public function view(MoonshineUser $user, MoonshineUserRole $model): bool
    {
        return $user->moonshineUserRole?->name === 'Super Admin';
    }

    public function create(MoonshineUser $user): bool
    {
        return $user->moonshineUserRole?->name === 'Super Admin';
    }

    public function update(MoonshineUser $user, MoonshineUserRole $model): bool
    {
        return $user->moonshineUserRole?->name === 'Super Admin';
    }

    public function delete(MoonshineUser $user, MoonshineUserRole $model): bool
    {
        return $user->moonshineUserRole?->name === 'Super Admin';
    }
}
