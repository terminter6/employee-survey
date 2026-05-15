<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Answer\Pages;

use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use App\MoonShine\Resources\Answer\AnswerResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;
use App\Models\Questionnaire;
use App\Models\Question;
use Throwable;


/**
 * @extends FormPage<AnswerResource>
 */
class AnswerFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Select::make('Опрос', 'questionnaire_id')
                    ->options(Questionnaire::all()->pluck('name', 'id')->toArray())
                    ->required(),
                Select::make('Вопрос', 'question_id')
                    ->options(Question::all()->pluck('text', 'id')->toArray())
                    ->required(),
                Number::make('Ответ', 'scale_value'),
                Text::make('Ответ', 'text_value')
                    ->customAttributes(['maxlength' => 255]),
            ]),
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    protected function formButtons(): ListOf
    {
        return parent::formButtons();
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
            'questionnaire_id' => ['required', 'integer', 'exists:questionnaires,id'],
            'question_id' => ['required', 'integer', 'exists:questions,id'],
            'text_value' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @param  FormBuilder  $component
     *
     * @return FormBuilder
     */
    protected function modifyFormComponent(FormBuilderContract $component): FormBuilderContract
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
