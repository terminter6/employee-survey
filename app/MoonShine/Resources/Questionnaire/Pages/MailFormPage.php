<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Questionnaire\Pages;

use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use MoonShine\UI\Fields\Hidden;
use MoonShine\UI\Components\Layout\Box;
use App\Models\Questionnaire;
use App\Models\EmployeeEmail;
use Illuminate\Http\Request;

/**
 * @extends Page<void>
 */
class MailFormPage extends Page
{
    protected bool $isLazy = false;

    protected ?Questionnaire $questionnaire = null;

    public function canSee(): bool
    {
        return false;
    }

    public function getTitle(): string
    {
        return 'Отправить опрос';
    }

    public function setQuestionnaire(Questionnaire $questionnaire): void
    {
        $this->questionnaire = $questionnaire;
    }

    public function getQuestionnaire(): Questionnaire
    {
        if ($this->questionnaire) {
            return $this->questionnaire;
        }

        $hash = request()->route('resourceItem');
        return Questionnaire::where('hash', $hash)->firstOrFail();
    }

    /**
     * @return array<string, string>
     */
    public function getBreadcrumbs(): array
    {
        return [
            '/admin/resource/questionnaire-resource/questionnaire-index-page' => 'Опросы',
            '#' => 'Отправить опрос',
        ];
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): array
    {
        $questionnaire = $this->getQuestionnaire();
        $employeeEmails = EmployeeEmail::pluck('email')->implode(',');

        return [
            Box::make([
                FormBuilder::make()
                    ->action(route('questionnaire.mail.send', $questionnaire))
                    ->fields([
                        Text::make('Опрос', 'questionnaire_name')
                            ->default($questionnaire->name)
                            ->readonly()
                            ->customAttributes(['maxlength' => 255]),

                        Text::make('Ссылка на опрос', 'survey_url')
                            ->default($questionnaire->url)
                            ->readonly()
                            ->customAttributes(['maxlength' => 255]),

                        Text::make('Тема письма', 'subject')
                            ->default('Приглашение пройти опрос')
                            ->required()
                            ->customAttributes(['maxlength' => 255]),

                        Textarea::make('Текст сообщения', 'message')
                            ->default("Здравствуйте!\n\nПриглашаем вас принять участие в опросе. Пожалуйста, перейдите по ссылке ниже и ответьте на вопросы.")
                            ->required()
                            ->customAttributes(['maxlength' => 255]),

                        Hidden::make('emails')
                            ->default($employeeEmails),
                    ])
                    ->submit('Отправить'),
            ]),
        ];
    }

    public function responseAfterSend(Request $request): mixed
    {
        return redirect('/admin/resource/questionnaire-resource/questionnaire-index-page');
    }
}
