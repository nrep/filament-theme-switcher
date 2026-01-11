<?php

namespace Isura\FilamentThemeSwitcher\Livewire;

use Filament\Facades\Filament;
use Isura\FilamentThemeSwitcher\FilamentThemeSwitcherPlugin;
use Isura\FilamentThemeSwitcher\ThemeManager;
use Livewire\Component;

class ThemeSwitcher extends Component
{
    public string $currentTheme = 'default';

    public array $themes = [];

    public function mount(): void
    {
        $themeManager = app(ThemeManager::class);
        $this->currentTheme = $themeManager->getCurrentTheme() ?? 'default';
        $this->themes = $themeManager->getAvailableThemes();
    }

    public function switchTheme(string $theme): void
    {
        $themeManager = app(ThemeManager::class);
        $themeManager->setTheme($theme);
        $this->currentTheme = $theme;

        $this->dispatch('theme-changed', theme: $theme);
        $this->redirect(request()->header('Referer') ?? Filament::getUrl());
    }

    public function render()
    {
        return view('filament-theme-switcher::livewire.theme-switcher');
    }
}
