<?php

namespace Isura\FilamentThemeSwitcher;

use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\Facades\Session;
use Isura\FilamentThemeSwitcher\Contracts\Theme;
use Isura\FilamentThemeSwitcher\Models\UserTheme;

class ThemeManager
{
    protected ?string $currentTheme = null;

    protected ?array $currentColors = null;

    protected ?string $darkMode = null;

    protected ?string $customCss = null;

    public function getCurrentTheme(): ?string
    {
        if ($this->currentTheme !== null) {
            return $this->currentTheme;
        }

        $plugin = $this->getPlugin();

        if ($plugin?->isUserMode() && auth()->check()) {
            $userTheme = UserTheme::where('user_id', auth()->id())
                ->where('panel_id', Filament::getCurrentPanel()?->getId())
                ->first();

            if ($userTheme) {
                $this->currentTheme = $userTheme->theme;
                $this->currentColors = $userTheme->colors;

                return $this->currentTheme;
            }
        }

        // Check global/session theme
        $this->currentTheme = Session::get('filament_theme', config('filament-theme-switcher.default_theme', 'default'));

        return $this->currentTheme;
    }

    public function getCurrentColors(): ?array
    {
        if ($this->currentColors !== null) {
            return $this->currentColors;
        }

        $plugin = $this->getPlugin();

        if ($plugin?->isUserMode() && auth()->check()) {
            $userTheme = UserTheme::where('user_id', auth()->id())
                ->where('panel_id', Filament::getCurrentPanel()?->getId())
                ->first();

            if ($userTheme && $userTheme->colors) {
                $this->currentColors = $userTheme->colors;

                return $this->currentColors;
            }
        } else {
            // For global mode, check session for custom colors
            $colors = Session::get('filament_theme_colors');
            if ($colors) {
                $this->currentColors = $colors;
                return $this->currentColors;
            }
        }

        return null;
    }

    public function setTheme(string $theme, ?array $colors = null, ?string $darkMode = null, ?string $customCss = null): void
    {
        $plugin = $this->getPlugin();

        if ($plugin?->isUserMode() && auth()->check()) {
            UserTheme::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'panel_id' => Filament::getCurrentPanel()?->getId(),
                ],
                [
                    'theme' => $theme,
                    'colors' => $colors,
                    'dark_mode' => $darkMode,
                    'custom_css' => $customCss,
                ]
            );
        } else {
            Session::put('filament_theme', $theme);
            
            if ($colors) {
                Session::put('filament_theme_colors', $colors);
            } else {
                Session::forget('filament_theme_colors');
            }

            if ($darkMode) {
                Session::put('filament_dark_mode', $darkMode);
            }

            if ($customCss) {
                Session::put('filament_custom_css', $customCss);
            } else {
                Session::forget('filament_custom_css');
            }
        }

        $this->currentTheme = $theme;
        $this->currentColors = $colors;
        $this->darkMode = $darkMode;
        $this->customCss = $customCss;
    }

    public function getThemeInstance(?string $themeName = null): ?Theme
    {
        $themeName = $themeName ?? $this->getCurrentTheme();
        $plugin = $this->getPlugin();

        if (!$plugin) {
            return null;
        }

        $themes = $plugin->getThemes();

        if (!isset($themes[$themeName])) {
            return null;
        }

        $themeClass = $themes[$themeName];

        return app($themeClass);
    }

    public function applyTheme(): void
    {
        $theme = $this->getThemeInstance();

        if (!$theme) {
            return;
        }

        // Get base theme colors
        $themeColors = $theme->getColors();
        
        // Get custom color overrides
        $customColors = $this->getCurrentColors();
        
        // Merge custom colors over theme colors
        $colors = $customColors ? array_merge($themeColors, array_filter($customColors)) : $themeColors;

        // Apply colors to Filament
        FilamentColor::register([
            'primary' => $this->parseColor($colors['primary'] ?? Color::Blue),
            'danger' => $this->parseColor($colors['danger'] ?? Color::Red),
            'gray' => $this->parseColor($colors['gray'] ?? Color::Zinc),
            'info' => $this->parseColor($colors['info'] ?? Color::Blue),
            'success' => $this->parseColor($colors['success'] ?? Color::Green),
            'warning' => $this->parseColor($colors['warning'] ?? Color::Amber),
        ]);
    }

    protected function parseColor(mixed $color): array
    {
        if (is_array($color)) {
            return $color;
        }

        if (is_string($color) && str_starts_with($color, '#')) {
            return Color::hex($color);
        }

        return $color;
    }

    protected function getPlugin(): ?FilamentThemeSwitcherPlugin
    {
        try {
            return FilamentThemeSwitcherPlugin::get();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getAvailableThemes(): array
    {
        $plugin = $this->getPlugin();

        if (!$plugin) {
            return [];
        }

        $themes = [];

        foreach ($plugin->getThemes() as $key => $themeClass) {
            $theme = app($themeClass);
            $themes[$key] = [
                'name' => $theme->getName(),
                'label' => $theme->getLabel(),
                'colors' => $theme->getColors(),
                'preview' => $theme->getPreviewColors(),
                'has_dark_mode' => method_exists($theme, 'hasDarkMode') ? $theme->hasDarkMode() : false,
            ];
        }

        return $themes;
    }

    public function getDarkMode(): string
    {
        if ($this->darkMode !== null) {
            return $this->darkMode;
        }

        $plugin = $this->getPlugin();

        if ($plugin?->isUserMode() && auth()->check()) {
            $userTheme = UserTheme::where('user_id', auth()->id())
                ->where('panel_id', Filament::getCurrentPanel()?->getId())
                ->first();

            if ($userTheme && $userTheme->dark_mode) {
                $this->darkMode = $userTheme->dark_mode;
                return $this->darkMode;
            }
        } else {
            $darkMode = Session::get('filament_dark_mode');
            if ($darkMode) {
                $this->darkMode = $darkMode;
                return $this->darkMode;
            }
        }

        return config('filament-theme-switcher.dark_mode.default', 'system');
    }

    public function setDarkMode(string $mode): void
    {
        $plugin = $this->getPlugin();

        if ($plugin?->isUserMode() && auth()->check()) {
            UserTheme::where('user_id', auth()->id())
                ->where('panel_id', Filament::getCurrentPanel()?->getId())
                ->update(['dark_mode' => $mode]);
        } else {
            Session::put('filament_dark_mode', $mode);
        }

        $this->darkMode = $mode;
    }

    public function getCustomCss(): ?string
    {
        if ($this->customCss !== null) {
            return $this->customCss;
        }

        $plugin = $this->getPlugin();

        if ($plugin?->isUserMode() && auth()->check()) {
            $userTheme = UserTheme::where('user_id', auth()->id())
                ->where('panel_id', Filament::getCurrentPanel()?->getId())
                ->first();

            if ($userTheme && $userTheme->custom_css) {
                $this->customCss = $userTheme->custom_css;
                return $this->customCss;
            }
        } else {
            $css = Session::get('filament_custom_css');
            if ($css) {
                $this->customCss = $css;
                return $this->customCss;
            }
        }

        return null;
    }

    public function isDarkModeEnabled(): bool
    {
        return config('filament-theme-switcher.dark_mode.enabled', true);
    }

    public function isCustomCssEnabled(): bool
    {
        return config('filament-theme-switcher.custom_css.enabled', true);
    }

    public function isImportExportEnabled(): bool
    {
        return config('filament-theme-switcher.import_export.enabled', true);
    }

    public function exportTheme(): array
    {
        return [
            'theme' => $this->getCurrentTheme(),
            'colors' => $this->getCurrentColors(),
            'dark_mode' => $this->getDarkMode(),
            'custom_css' => $this->getCustomCss(),
            'exported_at' => now()->toIso8601String(),
            'version' => '2.0',
        ];
    }

    public function importTheme(array $data): bool
    {
        if (!isset($data['theme'])) {
            return false;
        }

        $this->setTheme(
            $data['theme'],
            $data['colors'] ?? null,
            $data['dark_mode'] ?? null,
            $data['custom_css'] ?? null
        );

        return true;
    }
}
