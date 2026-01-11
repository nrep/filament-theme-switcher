<?php

namespace Isura\FilamentThemeSwitcher\Themes;

use Filament\Support\Colors\Color;

class SunsetTheme extends AbstractTheme
{
    public static function getName(): string
    {
        return 'sunset';
    }

    public function getLabel(): string
    {
        return __('filament-theme-switcher::theme-switcher.themes.sunset');
    }

    public function getColors(): array
    {
        return [
            'primary' => Color::Orange,
            'danger' => Color::Red,
            'gray' => Color::Stone,
            'info' => Color::Amber,
            'success' => Color::Lime,
            'warning' => Color::Yellow,
        ];
    }
}
