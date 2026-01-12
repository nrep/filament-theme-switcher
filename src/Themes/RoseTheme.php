<?php

namespace Isura\FilamentThemeSwitcher\Themes;

use Filament\Support\Colors\Color;

class RoseTheme extends AbstractTheme
{
    public static function getName(): string
    {
        return 'rose';
    }

    public function getLabel(): string
    {
        return __('filament-theme-switcher::theme-switcher.themes.rose');
    }

    public function getColors(): array
    {
        return [
            'primary' => Color::Rose,
            'danger' => Color::Red,
            'gray' => Color::Zinc,
            'info' => Color::Pink,
            'success' => Color::Emerald,
            'warning' => Color::Orange,
        ];
    }

    public function getDarkColors(): array
    {
        return [
            'primary' => Color::Pink,
            'danger' => Color::Rose,
            'gray' => Color::Slate,
            'info' => Color::Fuchsia,
            'success' => Color::Teal,
            'warning' => Color::Amber,
        ];
    }
}
