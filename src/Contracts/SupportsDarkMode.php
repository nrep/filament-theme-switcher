<?php

namespace Isura\FilamentThemeSwitcher\Contracts;

interface SupportsDarkMode
{
    /**
     * Check if this theme supports dark mode.
     */
    public function hasDarkMode(): bool;

    /**
     * Get the dark mode colors configuration.
     *
     * @return array{primary: mixed, danger: mixed, gray: mixed, info: mixed, success: mixed, warning: mixed}
     */
    public function getDarkColors(): array;
}
