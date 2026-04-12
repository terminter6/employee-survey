<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Question\Pages;

use MoonShine\Laravel\Pages\Crud\FormPage;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use App\MoonShine\Resources\Question\QuestionResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Components\Layout\Box;
use Throwable;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Select;
use App\Models\Question;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Checkbox;
use MoonShine\UI\Fields\Textarea;
/**
 * @extends FormPage<QuestionResource>
 */
class QuestionFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Text::make('Текст вопроса', 'text')
                    ->required(),
                Select::make('Тип', 'type')
                    ->options(Question::getTypes())
                    ->required(),
                Image::make('Изображение', 'image')
                    ->disk('public')
                    ->dir('questions'),
                Checkbox::make('Обязательный', 'is_required'),
                Textarea::make('Варианты ответов (каждый с новой строки)', 'options')
                    ->hint('Заполняется для типов с выбором вариантов')
                    ->customAttributes(['rows' => 4]),
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
            'text' => ['required', 'string', 'max:1000'],
            'type' => ['required', 'string'],
        ];
    }

    /**
     * @param  FormBuilder  $component
     *
     * @return FormBuilder
     */
    protected function modifyFormComponent(FormBuilderContract $component): FormBuilderContract
    {
        return $component->beforeSave(function (Model $item, array $data): array {
            if (isset($data['options']) && is_string($data['options'])) {
                $lines = array_filter(array_map('trim', explode("\n", $data['options'])));
                $data['options'] = json_encode(array_values($lines));
            }
            return $data;
        });
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
