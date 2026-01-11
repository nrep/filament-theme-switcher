<?php

namespace Isura\FilamentThemeSwitcher;

use Isura\FilamentThemeSwitcher\Livewire\ThemeSwitcher;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentThemeSwitcherServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-theme-switcher';

    public static string $viewNamespace = 'filament-theme-switcher';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasConfigFile()
            ->hasViews(static::$viewNamespace)
            ->hasMigration('create_user_themes_table')
            ->hasTranslations()
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('nrep/filament-theme-switcher');
            });
    }

    public function packageRegistered(): void
    {
        parent::packageRegistered();

        $this->app->singleton(ThemeManager::class, function () {
            return new ThemeManager();
        });
    }

    public function packageBooted(): void
    {
        parent::packageBooted();

        // Register Livewire component
        if (class_exists(Livewire::class)) {
            Livewire::component('theme-switcher', ThemeSwitcher::class);
        }

        // Publish assets
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/css' => public_path('vendor/filament-theme-switcher/css'),
            ], 'filament-theme-switcher-assets');
        }
    }
}
