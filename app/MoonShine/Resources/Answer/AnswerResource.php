<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Answer;

use Illuminate\Database\Eloquent\Model;
use App\Models\Answer;
use App\MoonShine\Resources\Answer\Pages\AnswerIndexPage;
use App\MoonShine\Resources\Answer\Pages\AnswerFormPage;
use App\MoonShine\Resources\Answer\Pages\AnswerDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<Answer, AnswerIndexPage, AnswerFormPage, AnswerDetailPage>
 */
class AnswerResource extends ModelResource
{
    protected string $model = Answer::class;

    protected string $title = 'Answers';
    
    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            AnswerIndexPage::class,
            AnswerFormPage::class,
            AnswerDetailPage::class,
        ];
    }
}
