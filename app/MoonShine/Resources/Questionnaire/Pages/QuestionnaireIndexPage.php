<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Questionnaire\Pages;

use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\Contracts\UI\FieldContract;
use App\MoonShine\Resources\Questionnaire\QuestionnaireResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Select;
use App\Models\QuestionnaireCategory;
use App\MoonShine\Resources\QuestionnaireCategory\QuestionnaireCategoryResource;
use MoonShine\UI\Fields\Url;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Fields\Number;
use MoonShine\Contracts\UI\ActionButtonContract;

/**
 * @extends IndexPage<QuestionnaireResource>
 */
class QuestionnaireIndexPage extends IndexPage
{
    protected bool $isLazy = true;

    /**
     * @return list<FieldContract>
     */


    protected function fields(): iterable
    {
        return [
            Text::make('Название', 'name')->sortable(),
            Select::make('Категория', 'category_id')
                ->options(QuestionnaireCategory::all()->pluck('name', 'id')->toArray())
                ->sortable(),
            Url::make('Ссылка', 'url')->blank(),
            Text::make('Прохождений', 'questionnaire_results_count'),
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons()->add(
            ActionButton::make('Рассылка', fn ($data) => route('questionnaire.mail.form', $data)),
            ActionButton::make('Результаты', function ($data) {
                $resource = moonshine()
                    ->getResources()
                    ->first(fn ($r) => $r instanceof \App\MoonShine\Resources\Questionnaire\QuestionnaireResource);

                if ($resource) {
                    $page = $resource->getPages()->first(fn ($p) => $p instanceof \App\MoonShine\Resources\Questionnaire\Pages\QuestionnaireResultsPage);
                    if ($page) {
                        $url = $page->getUrl();
                        return $url . '?questionnaire_id=' . $data->id;
                    }
                }

                return '#';
            })->primary()->icon('eye'),
        );
    }

    /**
     * @return list<FieldContract>
     */
    protected function filters(): iterable
    {
        return [];
    }
}
