<?php

namespace Isura\FilamentThemeSwitcher\Contracts;

interface HasCustomColors
{
    /**
     * Determine if the theme supports custom color overrides.
     */
    public function supportsCustomColors(): bool;

    /**
     * Get the customizable color keys.
     *
     * @return array<string>
     */
    public function getCustomizableColors(): array;
}
