<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Questionnaire\Pages;

use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;
use MoonShine\UI\Components\Layout\Flex;
use MoonShine\Apexcharts\Components\RawChartMetric;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Questionnaire;
use App\MoonShine\Resources\Questionnaire\QuestionnaireResource;

class QuestionStatisticsPage extends Page
{
    protected bool $isLazy = false;

    public function canSee(): bool
    {
        return true;
    }

    public function getTitle(): string
    {
        $question = $this->getQuestion();

        return $question
            ? 'Статистика: ' . $question->text
            : 'Статистика по вопросу';
    }

    public function getBreadcrumbs(): array
    {
        $resource = moonshine()
            ->getResources()
            ->first(fn ($r) => $r instanceof QuestionnaireResource);

        $indexUrl = $resource?->getPages()->first()?->getUrl() ?? '#';

        $questionnaireId = request('questionnaire_id');
        $questionnaire = Questionnaire::find($questionnaireId);

        $resultsPage = $resource?->getPages()->first(
            fn ($p) => $p instanceof QuestionnaireResultsPage
        );

        $resultsUrl = $resultsPage?->getUrl() . '?questionnaire_id=' . $questionnaireId ?? '#';

        return [
            $indexUrl => 'Опросы',
            $resultsUrl => $questionnaire ? 'Результаты: ' . $questionnaire->name : 'Результаты',
            '#' => $this->getTitle(),
        ];
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        $question = $this->getQuestion();

        if (! $question) {
            return [];
        }

        $total = Answer::where('question_id', $question->id)->count();
        $chartData = $this->getChartData($question);

        if ($question->type === 'text') {
            $labels = array_map(fn ($label) => $this->truncate($label, 35), array_keys($chartData));

            return [
                Flex::make([
                    ValueMetric::make('Всего ответов')
                        ->value($total),
                ])->justifyAlign('between'),

                RawChartMetric::make('Распределение ответов')
                    ->config([
                        'series' => [
                            ['name' => 'Количество', 'data' => array_values($chartData)],
                        ],
                        'chart' => [
                            'type' => 'bar',
                            'height' => max(300, count($chartData) * 40 + 100),
                        ],
                        'plotOptions' => [
                            'bar' => [
                                'horizontal' => true,
                                'borderRadius' => 4,
                                'distributed' => true,
                            ],
                        ],
                        'colors' => $this->generateColors(count($chartData)),
                        'xaxis' => [
                            'categories' => $labels,
                        ],
                        'dataLabels' => [
                            'enabled' => true,
                        ],
                        'legend' => [
                            'show' => false,
                        ],
                    ]),
            ];
        }

        return [
            Flex::make([
                ValueMetric::make('Всего ответов')
                    ->value($total),
            ])->justifyAlign('between'),

            RawChartMetric::make('Распределение ответов')
                ->config([
                    'series' => array_values($chartData),
                    'labels' => array_keys($chartData),
                    'chart' => [
                        'type' => 'donut',
                        'height' => 400,
                    ],
                    'dataLabels' => [
                        'enabled' => true,
                    ],
                    'plotOptions' => [
                        'pie' => [
                            'expandOnClick' => false,
                            'donut' => [
                                'labels' => [
                                    'show' => true,
                                    'total' => [
                                        'show' => true,
                                        'label' => 'Всего',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]),
        ];
    }

    private function getQuestion(): ?Question
    {
        return Question::find(request('question_id'));
    }

    /**
     * @return array<string, int>
     */
    private function getChartData(Question $question): array
    {
        $query = Answer::where('question_id', $question->id);

        if ($question->type === 'scale') {
            $data = $query
                ->selectRaw('scale_value as value, COUNT(*) as count')
                ->whereNotNull('scale_value')
                ->groupBy('scale_value')
                ->orderBy('scale_value')
                ->pluck('count', 'value')
                ->toArray();

            $filled = [];
            for ($i = 1; $i <= 5; $i++) {
                $filled[(string) $i] = $data[$i] ?? 0;
            }

            return $filled;
        }

        $data = $query
            ->selectRaw('text_value as value, COUNT(*) as count')
            ->whereNotNull('text_value')
            ->groupBy('text_value')
            ->orderByDesc('count')
            ->limit(15)
            ->pluck('count', 'value')
            ->toArray();

        return $data;
    }

    private function truncate(string $text, int $length = 35): string
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }

        return mb_substr($text, 0, $length) . '…';
    }

    /**
     * @return list<string>
     */
    private function generateColors(int $count): array
    {
        $colors = [];
        $goldenRatio = 0.618033988749895;
        $hue = 0.5;

        for ($i = 0; $i < $count; $i++) {
            $hue += $goldenRatio;
            $hue -= floor($hue);

            $h = (int) ($hue * 360);
            $s = 70 + ($i % 2) * 10;
            $l = 50 + ($i % 3) * 5;

            $colors[] = "hsl({$h}, {$s}%, {$l}%)";
        }

        return $colors;
    }
}
