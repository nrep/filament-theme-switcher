<?php

namespace Isura\FilamentThemeSwitcher\Pages;

use Filament\Pages\Page;
use Isura\FilamentThemeSwitcher\FilamentThemeSwitcherPlugin;

class ThemeBuilder extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static string $view = 'filament-theme-switcher::pages.theme-builder';

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('filament-theme-switcher::theme-switcher.theme_builder');
    }

    public static function getNavigationGroup(): ?string
    {
        return FilamentThemeSwitcherPlugin::get()->getNavigationGroup();
    }

    public function getTitle(): string
    {
        return __('filament-theme-switcher::theme-switcher.theme_builder');
    }

    public static function getSlug(): string
    {
        return 'theme-builder';
    }

    public static function shouldRegisterNavigation(): bool
    {
        $plugin = FilamentThemeSwitcherPlugin::get();

        return $plugin->shouldRegisterNavigation() && 
               $plugin->userCanViewThemesPage() && 
               config('filament-theme-switcher.theme_builder.enabled', true);
    }
}
