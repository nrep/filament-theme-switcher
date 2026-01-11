<?php

namespace Isura\FilamentThemeSwitcher\Themes;

use Filament\Support\Colors\Color;

class DefaultTheme extends AbstractTheme
{
    public static function getName(): string
    {
        return 'default';
    }

    public function getLabel(): string
    {
        return __('filament-theme-switcher::theme-switcher.themes.default');
    }

    public function getColors(): array
    {
        return [
            'primary' => Color::Blue,
            'danger' => Color::Red,
            'gray' => Color::Zinc,
            'info' => Color::Blue,
            'success' => Color::Green,
            'warning' => Color::Amber,
        ];
    }
}
