<?php

namespace Isura\FilamentThemeSwitcher\Themes;

use Filament\Support\Colors\Color;

class OceanTheme extends AbstractTheme
{
    public static function getName(): string
    {
        return 'ocean';
    }

    public function getLabel(): string
    {
        return __('filament-theme-switcher::theme-switcher.themes.ocean');
    }

    public function getColors(): array
    {
        return [
            'primary' => Color::Cyan,
            'danger' => Color::Rose,
            'gray' => Color::Slate,
            'info' => Color::Sky,
            'success' => Color::Teal,
            'warning' => Color::Amber,
        ];
    }
}
