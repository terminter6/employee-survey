<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Questionnaire\Pages;

use MoonShine\Laravel\Pages\Crud\FormPage;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use App\MoonShine\Resources\Questionnaire\QuestionnaireResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\Modal;
use MoonShine\UI\Components\FormBuilder as FormBuilderComponent;
use MoonShine\UI\Fields\Hidden;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Components\Layout\Flex;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use App\Models\QuestionnaireCategory;
use App\Models\Question;
use MoonShine\UI\Fields\Url;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Position;
use MoonShine\UI\Fields\Checkbox;
use MoonShine\UI\Fields\Textarea;
use MoonShine\Support\Enums\Color;
use MoonShine\Support\Enums\TextPosition;
use MoonShine\UI\Components\Badge;
use MoonShine\UI\Components\Thumbnail;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Json;
use Throwable;
use MoonShine\UI\Components\Layout\Divider;
use MoonShine\UI\Components\FlexibleRender;

/**
 * @extends FormPage<QuestionnaireResource>
 */
class QuestionnaireFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Text::make('Название', 'name')
                    ->required()
                    ->customAttributes(['maxlength' => 255]),
                Select::make('Категория', 'category_id')
                    ->options(QuestionnaireCategory::all()->pluck('name', 'id')->toArray())
                    ->required(),
                Date::make('Дата окончания', 'ends_at')
                    ->withTime()
                    ->customAttributes(['min' => now()->format('Y-m-d\TH:i')]),
            ]),
            FlexibleRender::make(<<<HTML
<script>
document.addEventListener('DOMContentLoaded', function() {
    const endsAtInput = document.querySelector('input[name="ends_at"]');
    if (!endsAtInput) return;

    endsAtInput.addEventListener('change', function() {
        const val = this.value;
        if (!val) return;

        const now = new Date();
        const selected = new Date(val);

        const diff = Math.abs(selected - now);
        if (diff < 60000) {
            const newDate = new Date(selected.getTime() + 3 * 60000);
            const yyyy = newDate.getFullYear();
            const mm = String(newDate.getMonth() + 1).padStart(2, '0');
            const dd = String(newDate.getDate()).padStart(2, '0');
            const hh = String(newDate.getHours()).padStart(2, '0');
            const mi = String(newDate.getMinutes()).padStart(2, '0');
            this.value = yyyy + '-' + mm + '-' + dd + 'T' + hh + ':' + mi;
        }
    });
});
</script>
HTML),
            Box::make($this->getQuestionsBlock())->name('Вопросы'),
        ];
    }

    private function getQuestionsBlock(): array
    {
        $questionnaire = $this->getResource()->getItem();
        $questions = $questionnaire?->questions ?? collect();

        $addButton = ActionButton::make('Добавить вопрос')
            ->icon('plus')
            ->primary()
            ->inModal(
                title: fn() => 'Новый вопрос',
                content: fn() => $this->getQuestionForm(),
                name: 'question-modal',
                builder: fn(Modal $modal) => $modal->wide()->closeOutside(false)
            );

        return [
            Flex::make([$addButton])->justifyAlign('start'),
            TableBuilder::make([
                Image::make('Изображение', 'image')
                    ->disk('public')
                    ->dir('questions'),
                Text::make('Вопрос', 'text'),
                Select::make('Тип', 'type')
                    ->options(Question::getTypes())
                    ->readonly(),
                Switcher::make('Обязательный', 'is_required'),
            ])
                ->items($questions)
                ->simple()
                ->buttons([
                    ActionButton::make()
                        ->icon('pencil')
                        ->primary()
                        ->inModal(
                            title: fn($data) => 'Редактировать вопрос',
                            content: fn($data) => $this->getQuestionForm($data),
                            name: fn($data) => "question-edit-modal-{$data->id}",
                            builder: fn(Modal $modal) => $modal->wide()->closeOutside(false)
                        ),
                    ActionButton::make()
                        ->icon('trash')
                        ->error()
                        ->inModal(
                            title: fn($data) => 'Удалить вопрос',
                            content: fn($data) => $this->getDeleteForm($data),
                            name: fn($data) => "question-delete-modal-{$data->id}",
                            builder: fn(Modal $modal) => $modal->closeOutside(false)
                        ),
                ]),
        ];
    }

    private function getQuestionForm($question = null): string
    {
        $questionnaire = $this->getResource()->getItem();
        if (!$questionnaire) {
            return '<p class="text-danger">Сначала сохраните опрос</p>';
        }

        $isEdit = $question !== null;
        $action = $isEdit
            ? route('questionnaire.questions.update', $question)
            : route('questionnaire.questions.store');
        $method = $isEdit ? 'PUT' : 'POST';

        $formData = $question ? $question->toArray() : [];
        $formData['questionnaire_id'] = $questionnaire->id;
        $options = $question ? $question->getOptionsArray() : [];

        $form = FormBuilderComponent::make($action)
            ->fields([
                Hidden::make('_method')->default($method),
                Hidden::make('questionnaire_id'),
                Text::make('Текст вопроса', 'text')
                    ->required()
                    ->customAttributes(['id' => 'question-text', 'maxlength' => 255]),
                Select::make('Тип вопроса', 'type')
                    ->options(Question::getTypes())
                    ->required()
                    ->customAttributes(['id' => 'question-type-select']),
                Image::make('Изображение', 'image')
                    ->disk('public')
                    ->dir('questions'),
                Switcher::make('Обязательный', 'is_required'),
                Divider::make(),
                Json::make('Варианты ответов', 'options')
                    ->fields([
                        Text::make('Ответ')
                            ->customAttributes(['maxlength' => 255]),
                    ])
                    ->removable()
                    ->creatable()
                    ->customAttributes(['id' => 'options-field']),
            ])
            ->fill($formData)
            ->submit('Сохранить', ['class' => 'btn-primary'])
            ->redirect(url()->previous())
            ->name($isEdit ? "question-edit-form-{$question->id}" : 'question-form');

        $formHtml = (string) $form;

        $typesWithOptions = json_encode(['single_choice', 'multiple_choice']);
        $script = <<<JS
        <script>
        (function() {
            const typeSelect = document.getElementById('question-type-select');
            const optionsField = document.getElementById('options-field');
            if (!typeSelect || !optionsField) return;

            const typesWithOptions = {$typesWithOptions};
            const optionsContainer = optionsField.closest('.form-group') || optionsField.parentElement;

            function updateVisibility() {
                const showOptions = typesWithOptions.includes(typeSelect.value);
                if (optionsContainer) {
                    optionsContainer.style.display = showOptions ? 'block' : 'none';
                }
            }

            typeSelect.addEventListener('change', updateVisibility);
            updateVisibility();
        })();
        </script>
        JS;

        return $formHtml . $script;
    }

    private function getDeleteForm(Question $question): string
    {
        $form = FormBuilderComponent::make(route('questionnaire.questions.destroy', $question))
            ->fields([
                Text::make('Вы уверены, что хотите удалить вопрос?', 'confirm')
                    ->readonly()
                    ->customAttributes(['value' => $question->text]),
            ])
            ->submit('Удалить', ['class' => 'btn-danger'])
            ->redirect(url()->previous())
            ->name("question-delete-form-{$question->id}");

        return (string) $form;
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
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:questionnaire_categories,id'],
            'ends_at' => ['nullable', 'after_or_equal:' . now()->startOfMinute()->toDateTimeString()],
        ];
    }

    public function validationMessages(): array
    {
        return [
            'ends_at.after_or_equal' => 'Дата окончания не может быть раньше текущего времени.',
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
