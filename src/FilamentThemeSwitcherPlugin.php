<?php

namespace Isura\FilamentThemeSwitcher;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\View\PanelsRenderHook;
use Isura\FilamentThemeSwitcher\Pages\ThemeBuilder;
use Isura\FilamentThemeSwitcher\Pages\ThemeSettings;
use Isura\FilamentThemeSwitcher\Themes\DefaultTheme;

class FilamentThemeSwitcherPlugin implements Plugin
{
    protected bool $registerNavigation = true;

    protected ?string $navigationGroup = null;

    protected ?string $navigationIcon = 'heroicon-o-swatch';

    protected ?int $navigationSort = null;

    protected ?string $navigationLabel = null;

    protected array $themes = [];

    protected bool $overrideDefaultThemes = false;

    protected ?Closure $canViewThemesPage = null;

    protected string $mode = 'global';

    public function getId(): string
    {
        return 'filament-theme-switcher';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->pages([
                ThemeSettings::class,
                ThemeBuilder::class,
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => view('filament-theme-switcher::components.custom-css')->render()
            );
    }

    public function boot(Panel $panel): void
    {
        // Boot logic if needed
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function registerNavigation(bool $register = true): static
    {
        $this->registerNavigation = $register;

        return $this;
    }

    public function shouldRegisterNavigation(): bool
    {
        return $this->registerNavigation;
    }

    public function navigationGroup(?string $group): static
    {
        $this->navigationGroup = $group;

        return $this;
    }

    public function getNavigationGroup(): ?string
    {
        return $this->navigationGroup;
    }

    public function navigationIcon(?string $icon): static
    {
        $this->navigationIcon = $icon;

        return $this;
    }

    public function getNavigationIcon(): ?string
    {
        return $this->navigationIcon;
    }

    public function navigationSort(?int $sort): static
    {
        $this->navigationSort = $sort;

        return $this;
    }

    public function getNavigationSort(): ?int
    {
        return $this->navigationSort;
    }

    public function navigationLabel(?string $label): static
    {
        $this->navigationLabel = $label;

        return $this;
    }

    public function getNavigationLabel(): ?string
    {
        return $this->navigationLabel ?? __('filament-theme-switcher::theme-switcher.navigation_label');
    }

    public function registerThemes(array $themes, bool $override = false): static
    {
        $this->themes = $themes;
        $this->overrideDefaultThemes = $override;

        return $this;
    }

    public function getThemes(): array
    {
        $defaultThemes = $this->overrideDefaultThemes ? [] : $this->getDefaultThemes();

        return array_merge($defaultThemes, $this->themes);
    }

    protected function getDefaultThemes(): array
    {
        return [
            'default' => Themes\DefaultTheme::class,
            'sunset' => Themes\SunsetTheme::class,
            'ocean' => Themes\OceanTheme::class,
            'forest' => Themes\ForestTheme::class,
            'midnight' => Themes\MidnightTheme::class,
            'rose' => Themes\RoseTheme::class,
            'amber' => Themes\AmberTheme::class,
        ];
    }

    public function canViewThemesPage(?Closure $callback): static
    {
        $this->canViewThemesPage = $callback;

        return $this;
    }

    public function getCanViewThemesPage(): ?Closure
    {
        return $this->canViewThemesPage;
    }

    public function userCanViewThemesPage(): bool
    {
        if ($this->canViewThemesPage === null) {
            return true;
        }

        return call_user_func($this->canViewThemesPage);
    }

    public function mode(string $mode): static
    {
        $this->mode = $mode;

        return $this;
    }

    public function getMode(): string
    {
        return config('filament-theme-switcher.mode', $this->mode);
    }

    public function isUserMode(): bool
    {
        return $this->getMode() === 'user';
    }

    public function isGlobalMode(): bool
    {
        return $this->getMode() === 'global';
    }
}
