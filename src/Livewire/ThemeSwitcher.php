<?php

namespace Isura\FilamentThemeSwitcher\Livewire;

use Filament\Facades\Filament;
use Isura\FilamentThemeSwitcher\FilamentThemeSwitcherPlugin;
use Isura\FilamentThemeSwitcher\ThemeManager;
use Livewire\Component;

class ThemeSwitcher extends Component
{
    public string $currentTheme = 'default';

    public string $darkMode = 'system';

    public array $themes = [];

    public bool $darkModeEnabled = true;

    public function mount(): void
    {
        $themeManager = app(ThemeManager::class);
        $this->currentTheme = $themeManager->getCurrentTheme() ?? 'default';
        $this->darkMode = $themeManager->getDarkMode();
        $this->themes = $themeManager->getAvailableThemes();
        $this->darkModeEnabled = $themeManager->isDarkModeEnabled();
    }

    public function switchTheme(string $theme): void
    {
        $themeManager = app(ThemeManager::class);
        $themeManager->setTheme($theme);
        $this->currentTheme = $theme;

        $this->dispatch('theme-changed', theme: $theme);
        $this->redirect(request()->header('Referer') ?? Filament::getUrl());
    }

    public function switchDarkMode(string $mode): void
    {
        $themeManager = app(ThemeManager::class);
        $themeManager->setDarkMode($mode);
        $this->darkMode = $mode;

        $this->dispatch('dark-mode-changed', mode: $mode);
        $this->redirect(request()->header('Referer') ?? Filament::getUrl());
    }

    public function render()
    {
        return view('filament-theme-switcher::livewire.theme-switcher');
    }
}
