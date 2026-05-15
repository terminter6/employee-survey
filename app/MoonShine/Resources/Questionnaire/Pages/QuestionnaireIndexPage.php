<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Questionnaire\Pages;

use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\Contracts\UI\FieldContract;
use App\MoonShine\Resources\Questionnaire\QuestionnaireResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Date;
use MoonShine\Support\Enums\Color;
use MoonShine\UI\Components\Badge;
use MoonShine\UI\Fields\Url;
use MoonShine\UI\Components\ActionButton;
use App\Models\QuestionnaireCategory;

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
            Text::make('Название', 'name')
                ->sortable()
                ->customWrapperAttributes(['class' => 'min-w-[200px]']),

            Select::make('Категория', 'category_id')
                ->options(QuestionnaireCategory::all()->pluck('name', 'id')->toArray())
                ->sortable()
                ->customWrapperAttributes(['class' => 'max-w-[160px]']),

            Date::make('Дата окончания', 'ends_at')
                ->format('d.m.Y H:i')
                ->customWrapperAttributes(['class' => 'whitespace-nowrap w-[120px]']),

            Text::make('Статус', 'status_label')
                ->customWrapperAttributes(['class' => 'whitespace-nowrap w-[90px]'])
                ->changePreview(function ($value, Text $field) {
                    $isActive = $field->getData()?->is_active;
                    return Badge::make($isActive ? 'Активен' : 'Неактивен', $isActive ? Color::SUCCESS : Color::GRAY);
                }),

            Url::make('Ссылка', 'url')
                ->blank()
                ->title(fn ($title, $field) => 'Открыть')
                ->customWrapperAttributes(['class' => 'whitespace-nowrap w-[100px]']),

            Text::make('Прохождений', 'questionnaire_results_count')
                ->customWrapperAttributes(['class' => 'text-center w-[80px]']),
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons()->add(
            ActionButton::make('Рассылка', fn ($data) => route('questionnaire.mail.form', $data))
                ->icon('envelope'),

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
        return [
            Text::make('Название', 'name')
                ->onApply(function (Builder $query, mixed $value, FieldContract $field) {
                    return $query->where('name', 'like', '%' . $value . '%');
                }),

            Select::make('Категория', 'category_id')
                ->options(QuestionnaireCategory::all()->pluck('name', 'id')->toArray()),

            Date::make('Дата окончания', 'ends_at'),

            Select::make('Статус', 'status')
                ->options([
                    '1' => 'Активен',
                    '0' => 'Неактивен',
                ])
                ->onApply(function (Builder $query, mixed $value, FieldContract $field) {
                    $now = now()->toDateTimeString();

                    if ($value === '1') {
                        return $query->where(function (Builder $q) use ($now) {
                            $q->whereNull('ends_at')
                              ->orWhere('ends_at', '>', $now);
                        });
                    }

                    return $query->whereNotNull('ends_at')
                        ->where('ends_at', '<=', $now);
                }),
        ];
    }
}
