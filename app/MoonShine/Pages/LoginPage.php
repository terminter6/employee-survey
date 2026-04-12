<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Core\Attributes\Layout;
use MoonShine\Laravel\Pages\Page;
use App\MoonShine\Layouts\MoonShineAuthLayout;
use MoonShine\UI\Components\Layout\Div;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\Password;
use MoonShine\UI\Components\Layout\ThemeSwitcher;

#[Layout(MoonShineAuthLayout::class)]
class LoginPage extends Page
{
    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        return [
            Div::make([
                ThemeSwitcher::make(),
                FormBuilder::make()
                    ->class('authentication-form w-full')
                    ->action($this->getRouter()->to('authenticate'))
                    ->errorsAbove(false)
                    ->fields([
                        Email::make('Email', 'username')
                            ->placeholder('Введите Email')
                            ->required()
                            ->customAttributes([
                                'autofocus' => true,
                                'autocomplete' => 'email',
                            ]),

                        Password::make('Пароль', 'password')
                            ->placeholder('Введите пароль')
                            ->required(),
                    ])->submit('Войти', [
                        'class' => 'btn-primary btn-lg w-full',
                    ]),
            ])->class('authentication-form flex flex-col items-end'),
        ];
    }
}
