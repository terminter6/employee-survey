<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\EmployeeEmail;

use App\Models\EmployeeEmail;
use App\MoonShine\Resources\EmployeeEmail\Pages\EmployeeEmailIndexPage;
use App\MoonShine\Resources\EmployeeEmail\Pages\EmployeeEmailFormPage;
use App\MoonShine\Resources\EmployeeEmail\Pages\EmployeeEmailDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Support\Enums\Ability;

/**
 * @extends ModelResource<EmployeeEmail, EmployeeEmailIndexPage, EmployeeEmailFormPage, EmployeeEmailDetailPage>
 */
class EmployeeEmailResource extends ModelResource
{
    protected string $model = EmployeeEmail::class;

    protected string $title = 'Email сотрудников';

    protected string $column = 'name';

    protected bool $createInModal = true;
    protected bool $editInModal = true;


    /**
     * @return string[]
     */
    protected function search(): array
    {
        return [
            'name',
            'email',
        ];
    }

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            EmployeeEmailIndexPage::class,
            EmployeeEmailFormPage::class,
        ];
    }
}
