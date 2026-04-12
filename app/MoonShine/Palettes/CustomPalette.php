<?php

declare(strict_types=1);

namespace App\MoonShine\Palettes;

use MoonShine\Contracts\ColorManager\PaletteContract;

final class CustomPalette implements PaletteContract
{
    public function getDescription(): string
    {
        return 'Custom palette';
    }

    public function getColors(): array
    {
        return [
            'body' => 'oklch(99% 0 0)',
            'primary' => 'oklch(0.6231 0.188 259.81)',
            'primary-text' => 'oklch(99% 0 0)',
            'secondary' => 'oklch(40% 0 0)',
            'secondary-text' => 'oklch(90% 0 0)',
            'success' => 'oklch(63.9% 0.218 142.495)',
            'success-text' => 'oklch(46% 0.156 142.511)',
            'warning' => 'oklch(80.88% 0.170358 75.3501)',
            'warning-text' => 'oklch(49.94% 0.09958 76.198)',
            'error' => 'oklch(58.9% 0.214 26.855)',
            'error-text' => 'oklch(37.06% 0.14554 26.762)',
            'info' => 'oklch(60.1% 0.219 257.63)',
            'info-text' => 'oklch(35.1% 0.11905 257.387)',
            'base' => [
                'text' => 'oklch(22% 0.005 248)',
                'stroke' => 'oklch(90% 0 0)',
                'default' => 'oklch(99% 0 0)',
                50 => 'oklch(97% 0.016 294)',
                100 => 'oklch(90% 0 0)',
                200 => 'oklch(93% 0.045 294)',
                300 => 'oklch(0.6231 0.188 259.81)',
                400 => 'oklch(86% 0.11 294)',
                500 => 'oklch(77% 0.16 294)',
                600 => 'oklch(67% 0.2 294)',
                700 => 'oklch(58% 0.24 294)',
                800 => 'oklch(48% 0.19 294)',
                900 => 'oklch(38% 0.14 294)',
            ]
        ];
    }

    public function getDarkColors(): array
    {
        return [
            'body' => 'oklch(18% 0 0)',
            'primary' => 'oklch(0.6231 0.188 259.81)',
            'primary-text' => 'oklch(99% 0 0)',
            'secondary' => 'oklch(22% 0 0)',
            'secondary-text' => 'oklch(90% 0 0)',
            'success' => 'oklch(63.9% 0.218 142.495)',
            'success-text' => 'oklch(93% 0.119 144)',
            'warning' => 'oklch(89% 0.182 95)',
            'warning-text' => 'oklch(99% 0.07 108)',
            'error' => 'oklch(59% 0.214 26.8)',
            'error-text' => 'oklch(67% 0.217 25.3)',
            'info' => 'oklch(60.1% 0.219 257.63)',
            'info-text' => 'oklch(88% 0.064 244)',
            'base' => [
                'text' => 'oklch(90% 0 0)',
                'stroke' => 'oklch(0.3029 0 259.81)',
                'default' => 'oklch(22% 0 0)',
                50 => 'oklch(24% 0 0)',
                100 => 'oklch(0.3516 0 292.72)',
                200 => 'oklch(33% 0 0)',
                300 => 'oklch(0.6231 0.188 259.81)',
                400 => 'oklch(46% 0 0)',
                500 => 'oklch(54% 0 0)',
                600 => 'oklch(63% 0 0)',
                700 => 'oklch(72% 0 0)',
                800 => 'oklch(80% 0 0)',
                900 => 'oklch(87% 0 0)',
            ],
        ];
    }
}
