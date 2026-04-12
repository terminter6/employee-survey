<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use MoonShine\Laravel\Layouts\BaseLayout;
use App\MoonShine\Palettes\CustomPalette;
use MoonShine\UI\Components\Layout\{Body, Div, Html, Layout};
use MoonShine\UI\Components\Components;
use MoonShine\UI\Components\Heading;
use MoonShine\UI\Components\Layout\Logo;

final class MoonShineAuthLayout extends BaseLayout
{
    /**
     * @var null|class-string<PaletteContract>
     */
    protected ?string $palette = CustomPalette::class;

    protected ?string $title = null;

    protected ?string $description = null;

    public function title(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title ?? 'Авторизация';
    }

    protected function getLogoComponent(): Logo
    {
        return Logo::make(
            href: '/',
            logo: asset('images/logo-dark.svg'),
            title: 'Nethammer'
        );
    }

    public function build(): Layout
    {
        return Layout::make([
            Html::make([
                $this->getHeadComponent(),
                Body::make([
                    Div::make([
                        Div::make([
                            $this->getLogoComponent(),
                        ])->class('authentication-logo'),

                        Div::make([
                            Components::make($this->getPage()->getComponents()),
                        ])->class('authentication-content'),

                    ])->class('authentication'),
                ]),
            ])
                ->customAttributes([
                    'lang' => $this->getHeadLang(),
                ])
                ->withAlpineJs()
                ->when(
                    $this->hasThemes() || $this->isAlwaysDark(),
                    fn (Html $html): Html => $html->withThemes($this->isAlwaysDark())
                ),
        ]);
    }
}
