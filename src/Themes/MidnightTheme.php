<?php

namespace Isura\FilamentThemeSwitcher\Themes;

use Filament\Support\Colors\Color;

class MidnightTheme extends AbstractTheme
{
    public static function getName(): string
    {
        return 'midnight';
    }

    public function getLabel(): string
    {
        return __('filament-theme-switcher::theme-switcher.themes.midnight');
    }

    public function getColors(): array
    {
        return [
            'primary' => Color::Indigo,
            'danger' => Color::Rose,
            'gray' => Color::Slate,
            'info' => Color::Violet,
            'success' => Color::Emerald,
            'warning' => Color::Amber,
        ];
    }

    public function getDarkColors(): array
    {
        return [
            'primary' => Color::Violet,
            'danger' => Color::Pink,
            'gray' => Color::Zinc,
            'info' => Color::Purple,
            'success' => Color::Teal,
            'warning' => Color::Yellow,
        ];
    }
}
