<?php

namespace App\Policies;

use App\Models\MoonshineUser;
use App\Models\Questionnaire;

class QuestionnairePolicy
{
    /**
     * Determine if the user can view any models.
     */
    public function viewAny(MoonshineUser $user): bool
    {
        return in_array($user->moonshineUserRole?->name, ['Super Admin', 'Администратор опросов']);
    }

    /**
     * Determine if the user can view the model.
     */
    public function view(MoonshineUser $user, Questionnaire $model): bool
    {
        return in_array($user->moonshineUserRole?->name, ['Super Admin', 'Администратор опросов']);
    }

    /**
     * Determine if the user can create models.
     */
    public function create(MoonshineUser $user): bool
    {
        return in_array($user->moonshineUserRole?->name, ['Super Admin', 'Администратор опросов']);
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(MoonshineUser $user, Questionnaire $model): bool
    {
        return in_array($user->moonshineUserRole?->name, ['Super Admin', 'Администратор опросов']);
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(MoonshineUser $user, Questionnaire $model): bool
    {
        return in_array($user->moonshineUserRole?->name, ['Super Admin', 'Администратор опросов']);
    }

    /**
     * Determine if the user can restore the model.
     */
    public function restore(MoonshineUser $user, Questionnaire $model): bool
    {
        return in_array($user->moonshineUserRole?->name, ['Super Admin', 'Администратор опросов']);
    }

    /**
     * Determine if the user can permanently delete the model.
     */
    public function forceDelete(MoonshineUser $user, Questionnaire $model): bool
    {
        return in_array($user->moonshineUserRole?->name, ['Super Admin', 'Администратор опросов']);
    }
}
