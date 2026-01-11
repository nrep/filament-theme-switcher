<?php

namespace Isura\FilamentThemeSwitcher\Contracts;

interface Theme
{
    /**
     * Get the unique identifier for the theme.
     */
    public static function getName(): string;

    /**
     * Get the display label for the theme.
     */
    public function getLabel(): string;

    /**
     * Get the theme colors configuration.
     *
     * @return array{primary: mixed, danger: mixed, gray: mixed, info: mixed, success: mixed, warning: mixed}
     */
    public function getColors(): array;

    /**
     * Get preview colors for the theme selector UI.
     *
     * @return array{primary: string, secondary: string, accent: string}
     */
    public function getPreviewColors(): array;
}
