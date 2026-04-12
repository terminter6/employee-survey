<?php

declare(strict_types=1);

namespace App\MoonShine\Forms;

use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\Contracts\UI\FormContract;
use MoonShine\Support\Traits\Makeable;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\Password;
use MoonShine\UI\Fields\Switcher;

final class CustomLoginForm implements FormContract
{
    use Makeable;

    public function __construct(
        private readonly string $action,
        private CoreContract $core
    ) {
    }

    public function __invoke(): FormBuilderContract
    {
        return FormBuilder::make()
            ->class('authentication-form')
            ->action($this->action)
            ->errorsAbove(false)
            ->fields([
                Email::make('Email', 'email')
                    ->placeholder('user@gmail.com')
                    ->required()
                    ->customAttributes([
                        'autofocus' => true,
                        'autocomplete' => 'email',
                    ]),

                Password::make('Пароль', 'password')
                    ->placeholder('********')
                    ->required(),
            ])->submit('Войти', [
                'class' => 'btn-primary btn-lg w-full',
            ]);
    }
}
