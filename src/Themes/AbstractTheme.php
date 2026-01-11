<?php

namespace Isura\FilamentThemeSwitcher\Themes;

use Filament\Support\Colors\Color;
use Isura\FilamentThemeSwitcher\Contracts\HasCustomColors;
use Isura\FilamentThemeSwitcher\Contracts\Theme;

abstract class AbstractTheme implements Theme, HasCustomColors
{
    public function supportsCustomColors(): bool
    {
        return true;
    }

    public function getCustomizableColors(): array
    {
        return ['primary', 'danger', 'gray', 'info', 'success', 'warning'];
    }

    public function getPreviewColors(): array
    {
        $colors = $this->getColors();

        return [
            'primary' => $this->extractHexColor($colors['primary'] ?? Color::Blue),
            'secondary' => $this->extractHexColor($colors['gray'] ?? Color::Zinc),
            'accent' => $this->extractHexColor($colors['success'] ?? Color::Green),
        ];
    }

    protected function extractHexColor(mixed $color): string
    {
        if (is_string($color) && str_starts_with($color, '#')) {
            return $color;
        }

        if (is_array($color) && isset($color[500])) {
            return $this->rgbToHex($color[500]);
        }

        return '#3b82f6';
    }

    protected function rgbToHex(string $rgb): string
    {
        if (preg_match('/(\d+),\s*(\d+),\s*(\d+)/', $rgb, $matches)) {
            return sprintf('#%02x%02x%02x', (int) $matches[1], (int) $matches[2], (int) $matches[3]);
        }

        return '#3b82f6';
    }
}
