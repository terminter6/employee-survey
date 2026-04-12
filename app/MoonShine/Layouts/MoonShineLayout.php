<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use MoonShine\Laravel\Layouts\AppLayout;
use App\MoonShine\Palettes\CustomPalette;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Contracts\ColorManager\PaletteContract;
use App\MoonShine\Resources\QuestionnaireCategory\QuestionnaireCategoryResource;
use MoonShine\MenuManager\MenuItem;
use App\MoonShine\Resources\Questionnaire\QuestionnaireResource;
use App\MoonShine\Resources\Question\QuestionResource;
use App\MoonShine\Resources\Answer\AnswerResource;
use App\MoonShine\Resources\EmployeeEmail\EmployeeEmailResource;
use MoonShine\UI\Components\Layout\Logo;
use Illuminate\Support\Facades\Auth;

final class MoonShineLayout extends AppLayout
{

    /**
     * @var null|class-string<PaletteContract>
     */
    protected ?string $palette = CustomPalette::class;

    protected function getLogoComponent(): Logo
    {
        return Logo::make(
            href: '/',
            logo: asset('images/logo-dark.svg'),
            title: 'Nethammer'
        );
    }

    protected function menu(): array
    {
        $user = Auth::user();
        $isSuperAdmin = $user?->moonshineUserRole?->name === 'Super Admin';

        if ($isSuperAdmin) {
            $menu = [
                MenuItem::make(\MoonShine\Laravel\Resources\MoonShineUserResource::class, 'Администраторы'),
                MenuItem::make(\MoonShine\Laravel\Resources\MoonShineUserRoleResource::class, 'Роли'),
                MenuItem::make(QuestionnaireCategoryResource::class, 'Категории опросов'),
                MenuItem::make(QuestionnaireResource::class, 'Опросы'),
            ];
        } else {
            $menu = [
                MenuItem::make(QuestionnaireCategoryResource::class, 'Категории опросов'),
                MenuItem::make(QuestionnaireResource::class, 'Опросы'),
            ];
        }

        if ($user) {
            $menu[] = MenuItem::make(EmployeeEmailResource::class, 'Email сотрудников');
        }

        return $menu;
    }

    protected function isSystemMenuVisible(): bool
    {
        $user = Auth::user();
        return $user?->moonshineUserRole?->name === 'Super Admin';
    }


    protected bool $getFooterMenu = true;

    protected function getFooterCopyright(): string
    {
        return "";
    }

    protected function getFooterMenu(): array
    {
        return [""=>""];
    }
}
