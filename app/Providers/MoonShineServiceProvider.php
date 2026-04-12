<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Laravel\DependencyInjection\MoonShine;
use MoonShine\Laravel\DependencyInjection\MoonShineConfigurator;
use App\MoonShine\Resources\MoonShineUser\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRole\MoonShineUserRoleResource;
use App\MoonShine\Resources\QuestionnaireCategory\QuestionnaireCategoryResource;
use App\MoonShine\Resources\Questionnaire\QuestionnaireResource;
use App\MoonShine\Resources\Question\QuestionResource;
use App\MoonShine\Resources\Answer\AnswerResource;
use App\MoonShine\Resources\EmployeeEmail\EmployeeEmailResource;

class MoonShineServiceProvider extends ServiceProvider
{
    /**
     * @param  CoreContract<MoonShineConfigurator>  $core
     */
    public function boot(CoreContract $core): void
    {
        $core
            ->resources([
                MoonShineUserResource::class,
                MoonShineUserRoleResource::class,
                QuestionnaireCategoryResource::class,
                QuestionnaireResource::class,
                QuestionResource::class,
                AnswerResource::class,
                EmployeeEmailResource::class,
            ])
            ->pages([
                ...$core->getConfig()->getPages(),
            ])
            ->autoload()
        ;
    }
}
