<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Questionnaire;
use App\Models\QuestionnaireResult;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;

class Dashboard extends Page
{
    /**
     * @return array<string, string>
     */
    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle()
        ];
    }

    public function getTitle(): string
    {
        return $this->title ?: 'Dashboard';
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        $questionnairesCount = Questionnaire::count();
        $questionnaireResultsCount = QuestionnaireResult::count();

        return [
            Grid::make([
                ValueMetric::make(__('Опросов'))
                    ->value($questionnairesCount),

                ValueMetric::make(__('Прохождений опросов'))
                    ->value($questionnaireResultsCount),
            ]),
        ];
    }
}

