<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\QuestionnaireCategory;

use Illuminate\Database\Eloquent\Model;
use App\Models\QuestionnaireCategory;
use App\MoonShine\Resources\QuestionnaireCategory\Pages\QuestionnaireCategoryIndexPage;
use App\MoonShine\Resources\QuestionnaireCategory\Pages\QuestionnaireCategoryFormPage;
use App\MoonShine\Resources\QuestionnaireCategory\Pages\QuestionnaireCategoryDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<QuestionnaireCategory, QuestionnaireCategoryIndexPage, QuestionnaireCategoryFormPage, QuestionnaireCategoryDetailPage>
 */
class QuestionnaireCategoryResource extends ModelResource
{
    protected string $model = QuestionnaireCategory::class;

    protected string $title = 'Категории опросов';
    protected string $column = 'name';

    protected bool $createInModal = true;
    protected bool $editInModal = true;

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            QuestionnaireCategoryIndexPage::class,
            QuestionnaireCategoryFormPage::class,
        ];
    }
}