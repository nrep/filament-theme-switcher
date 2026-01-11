<?php

namespace Isura\FilamentThemeSwitcher\Themes;

use Filament\Support\Colors\Color;

class AmberTheme extends AbstractTheme
{
    public static function getName(): string
    {
        return 'amber';
    }

    public function getLabel(): string
    {
        return __('filament-theme-switcher::theme-switcher.themes.amber');
    }

    public function getColors(): array
    {
        return [
            'primary' => Color::Amber,
            'danger' => Color::Red,
            'gray' => Color::Stone,
            'info' => Color::Yellow,
            'success' => Color::Lime,
            'warning' => Color::Orange,
        ];
    }
}
