<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Questionnaire\Pages;

use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\Contracts\UI\FieldContract;
use App\MoonShine\Resources\Questionnaire\QuestionnaireResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Select;
use App\MoonShine\Resources\Question\QuestionResource;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use App\Models\Question;
use App\MoonShine\Resources\Answer\AnswerResource;
use MoonShine\UI\Components\ActionButton;
use Throwable;
use MoonShine\Laravel\Fields\Relationships\HasManyThrough;
/**
 * @extends DetailPage<QuestionnaireResource>
 */
class QuestionnaireDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            HasMany::make('Вопросы', 'questions', QuestionResource::class)->fields([
                Text::make('Текст вопроса' , 'text'),
                Select::make('Тип вопроса' , 'type')->options(Question::getTypes()),
            ])->disableOutside()->sortable(),
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons()->add(
            ActionButton::make('Рассылка', fn ($data) => route('questionnaire.mail.form', $data))
        );
    }

    /**
     * @param  TableBuilder  $component
     *
     * @return TableBuilder
     */
    protected function modifyDetailComponent(ComponentContract $component): ComponentContract
    {
        return $component;
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            ...parent::topLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer()
        ];
    }
}
