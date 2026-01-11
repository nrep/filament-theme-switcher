<?php

namespace Isura\FilamentThemeSwitcher\Contracts;

use Filament\Panel;

interface CanModifyPanel
{
    /**
     * Modify the panel configuration when this theme is active.
     */
    public function modifyPanel(Panel $panel): Panel;
}
