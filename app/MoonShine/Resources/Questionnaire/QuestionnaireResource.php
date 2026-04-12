<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Questionnaire;

use Illuminate\Database\Eloquent\Model;
use App\Models\Questionnaire;
use App\MoonShine\Resources\Questionnaire\Pages\QuestionnaireIndexPage;
use App\MoonShine\Resources\Questionnaire\Pages\QuestionnaireFormPage;
use App\MoonShine\Resources\Questionnaire\Pages\QuestionnaireDetailPage;
use App\MoonShine\Resources\Questionnaire\Pages\MailFormPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Support\Enums\Ability;

/**
 * @extends ModelResource<Questionnaire, QuestionnaireIndexPage, QuestionnaireFormPage, QuestionnaireDetailPage>
 */
class QuestionnaireResource extends ModelResource
{
    protected string $model = Questionnaire::class;

    protected string $title = 'Опросы';

    protected string $column = 'name';

    public function can(Ability|string $ability): bool
    {
        return true;
    }

    protected function modifyQueryBuilder(Builder $builder): Builder
    {
        return $builder->withCount('questionnaireResults');
    }

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            QuestionnaireIndexPage::class,
            QuestionnaireFormPage::class,
            QuestionnaireDetailPage::class,
            MailFormPage::class,
            \App\MoonShine\Resources\Questionnaire\Pages\QuestionnaireResultsPage::class,
        ];
    }

    protected function afterCreated(DataWrapperContract $item): DataWrapperContract
    {
        $this->fillQuestionnaireId($item);
        return $item;
    }

    protected function afterUpdated(DataWrapperContract $item): DataWrapperContract
    {
        $this->fillQuestionnaireId($item);
        return $item;
    }

    private function fillQuestionnaireId(DataWrapperContract $item): void
    {
        $item->getOriginal()->questions()->whereNull('questionnaire_id')->update([
            'questionnaire_id' => $item->getOriginal()->id,
        ]);
    }
}
