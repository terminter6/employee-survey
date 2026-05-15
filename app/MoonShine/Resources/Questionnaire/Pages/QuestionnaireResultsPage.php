<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Questionnaire\Pages;

use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Components\Layout\Flex;
use MoonShine\UI\Components\Layout\Divider;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Fields\Text;
use MoonShine\Apexcharts\Components\LineChartMetric;
use MoonShine\Apexcharts\Support\SeriesItem;
use App\Models\Questionnaire;
use App\Models\QuestionnaireResult;
use App\MoonShine\Resources\Questionnaire\QuestionnaireResource;
use App\MoonShine\Resources\Questionnaire\Pages\QuestionStatisticsPage;

class QuestionnaireResultsPage extends Page
{
    protected bool $isLazy = false;

    public function getTitle(): string
    {
        $questionnaireId = request('questionnaire_id');
        $questionnaire = Questionnaire::find($questionnaireId);
        
        return $questionnaire 
            ? __('Результаты: ') . $questionnaire->name 
            : __('Результаты опроса');
    }

    /**
     * @return array<string, string>
     */
    public function getBreadcrumbs(): array
    {
        $resource = moonshine()
            ->getResources()
            ->first(fn ($r) => $r instanceof QuestionnaireResource);
        
        $indexUrl = $resource?->getPages()->first()?->getUrl() ?? '#';
        
        return [
            $indexUrl => __('Опросы'),
            '#' => $this->getTitle(),
        ];
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        $questionnaireId = request('questionnaire_id');
        $questionnaire = Questionnaire::find($questionnaireId);
        
        if (!$questionnaire) {
            return [];
        }

        $resultsCount = QuestionnaireResult::where('questionnaire_id', $questionnaireId)->count();

        $exportButton = ActionButton::make('Экспорт в Excel', route('questionnaire.export', $questionnaire))
            ->icon('arrow-down-tray')
            ->success();

        return [
            Flex::make([
                ValueMetric::make(__('Прохождений'))
                    ->value($resultsCount),
                $exportButton,
            ])->justifyAlign('between'),

            LineChartMetric::make(__('Прохождения по дням'))
                ->series([
                    SeriesItem::make(__('Прохождений'), $this->getDailyPassingsData($questionnaireId))
                        ->area(),
                ]),

            Divider::make(__('Результаты'))->centered(),

            TableBuilder::make($this->getQuestionsFields($questionnaireId))
                ->items($this->getAnswersData($questionnaireId))
                ->simple(),
        ];
    }

    protected function getQuestionsFields(int|string $questionnaireId): array
    {
        $questions = \App\Models\Question::where('questionnaire_id', $questionnaireId)
            ->orderBy('id')
            ->get();

        $resource = moonshine()
            ->getResources()
            ->first(fn ($r) => $r instanceof QuestionnaireResource);

        $statisticsPage = $resource?->getPages()->first(
            fn ($p) => $p instanceof QuestionStatisticsPage
        );

        $fields = [];
        foreach ($questions as $question) {
            $url = $statisticsPage
                ? $statisticsPage->getUrl() . '?questionnaire_id=' . $questionnaireId . '&question_id=' . $question->id
                : '#';

            $fields[] = Text::make(
                '<a href="' . $url . '" class="text-primary hover:underline">' . e($question->text) . '</a>',
                'question_' . $question->id
            )->modifyRawValue(fn ($raw, $index, $ctx) => $ctx['question_' . $question->id] ?? '—');
        }

        return $fields;
    }

    protected function getAnswersData(int|string $questionnaireId): iterable
    {
        $questions = \App\Models\Question::where('questionnaire_id', $questionnaireId)
            ->orderBy('id')
            ->get()
            ->pluck('id');

        $results = QuestionnaireResult::where('questionnaire_id', $questionnaireId)
            ->with(['answers'])
            ->latest()
            ->get();

        $data = [];
        foreach ($results as $result) {
            $row = ['id' => $result->id];

            foreach ($questions as $questionId) {
                $question = \App\Models\Question::find($questionId);
                $answers = $result->answers->where('question_id', $questionId);

                if ($question && $question->type === 'multiple_choice') {
                    $values = $answers->pluck('text_value')->filter()->toArray();
                    $row['question_' . $questionId] = !empty($values) ? implode(', ', $values) : '—';
                } else {
                    $answer = $answers->first();
                    $row['question_' . $questionId] = $answer?->text_value ?? $answer?->scale_value ?? '—';
                }
            }

            $data[] = $row;
        }

        return $data;
    }

    protected function getDailyPassingsData(int|string $questionnaireId): array
    {
        $dailyPassings = QuestionnaireResult::where('questionnaire_id', $questionnaireId)
            ->get()
            ->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->created_at)->format('d.m.Y');
            })
            ->map(fn ($group) => $group->count())
            ->toArray();

        ksort($dailyPassings);

        return $dailyPassings;
    }
}
