<?php

namespace Isura\FilamentThemeSwitcher\Themes;

use Filament\Support\Colors\Color;

class ForestTheme extends AbstractTheme
{
    public static function getName(): string
    {
        return 'forest';
    }

    public function getLabel(): string
    {
        return __('filament-theme-switcher::theme-switcher.themes.forest');
    }

    public function getColors(): array
    {
        return [
            'primary' => Color::Emerald,
            'danger' => Color::Red,
            'gray' => Color::Neutral,
            'info' => Color::Teal,
            'success' => Color::Green,
            'warning' => Color::Yellow,
        ];
    }
}
