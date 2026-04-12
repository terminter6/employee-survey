<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\MoonshineUser;
use App\Models\Questionnaire;
use App\Models\QuestionnaireCategory;
use App\Models\Question;
use App\Models\Answer;
use MoonShine\Laravel\Models\MoonshineUserRole;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        MoonshineUser::class => \App\Policies\MoonShineUserPolicy::class,
        MoonshineUserRole::class => \App\Policies\MoonShineUserRolePolicy::class,
        Questionnaire::class => \App\Policies\QuestionnairePolicy::class,
        QuestionnaireCategory::class => \App\Policies\QuestionnaireCategoryPolicy::class,
        Question::class => \App\Policies\QuestionPolicy::class,
        Answer::class => \App\Policies\AnswerPolicy::class,
        EmployeeEmail::class => \App\Policies\EmployeeEmailPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
