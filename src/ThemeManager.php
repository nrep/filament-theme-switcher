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

    public function setThemeBuilderState(array $state): void
    {
        $plugin = $this->getPlugin();

        if ($plugin?->isUserMode() && auth()->check()) {
            UserTheme::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'panel_id' => Filament::getCurrentPanel()?->getId(),
                ],
                [
                    'theme' => $state['theme'] ?? $this->getCurrentTheme() ?? 'default',
                    'colors' => $state['colors'] ?? null,
                    'dark_mode' => $state['dark_mode'] ?? $this->getDarkMode(),
                    'custom_css' => $state['custom_css'] ?? null,
                    'fonts' => $state['fonts'] ?? null,
                    'components' => $state['components'] ?? null,
                    'spacing' => $state['spacing'] ?? null,
                    'brand' => $state['brand'] ?? null,
                ]
            );
        } else {
            Session::put('filament_theme_builder_state', $state);
            
            if (isset($state['theme'])) {
                Session::put('filament_theme', $state['theme']);
            }
            if (isset($state['colors'])) {
                Session::put('filament_theme_colors', $state['colors']);
            }
            if (isset($state['dark_mode'])) {
                Session::put('filament_dark_mode', $state['dark_mode']);
            }
            if (isset($state['custom_css'])) {
                Session::put('filament_custom_css', $state['custom_css']);
            }
        }

        $this->currentTheme = $state['theme'] ?? $this->currentTheme;
        $this->currentColors = $state['colors'] ?? $this->currentColors;
        $this->darkMode = $state['dark_mode'] ?? $this->darkMode;
        $this->customCss = $state['custom_css'] ?? $this->customCss;
    }

    public function getThemeBuilderState(): array
    {
        $plugin = $this->getPlugin();
        
        $defaultState = [
            'colors' => [
                'primary' => '#3b82f6',
                'danger' => '#ef4444',
                'success' => '#22c55e',
                'warning' => '#f59e0b',
                'info' => '#06b6d4',
                'gray' => '#6b7280',
            ],
            'fonts' => [
                'heading' => ['family' => 'Inter', 'weight' => 600, 'size' => 'default'],
                'body' => ['family' => 'Inter', 'weight' => 400, 'size' => 'default'],
                'mono' => ['family' => 'JetBrains Mono', 'weight' => 400, 'size' => 'default'],
            ],
            'components' => [
                'sidebar' => ['background' => '', 'text_color' => '', 'border_radius' => '0'],
                'header' => ['background' => '', 'text_color' => '', 'sticky' => true],
                'cards' => ['background' => '', 'border_radius' => '8', 'shadow' => 'md'],
                'buttons' => ['border_radius' => '6', 'shadow' => 'sm'],
            ],
            'spacing' => [
                'content_padding' => '16',
                'card_padding' => '16',
                'sidebar_width' => '280',
            ],
            'brand' => [
                'logo' => null,
                'logo_dark' => null,
                'favicon' => null,
                'login_style' => 'centered',
                'login_background' => null,
                'show_app_name' => true,
            ],
            'custom_css' => '',
        ];

        if ($plugin?->isUserMode() && auth()->check()) {
            $userTheme = UserTheme::where('user_id', auth()->id())
                ->where('panel_id', Filament::getCurrentPanel()?->getId())
                ->first();

            if ($userTheme) {
                return [
                    'colors' => $userTheme->colors ? array_merge($defaultState['colors'], $userTheme->colors) : $defaultState['colors'],
                    'fonts' => $userTheme->fonts ? array_merge($defaultState['fonts'], $userTheme->fonts) : $defaultState['fonts'],
                    'components' => $userTheme->components ? array_merge($defaultState['components'], $userTheme->components) : $defaultState['components'],
                    'spacing' => $userTheme->spacing ? array_merge($defaultState['spacing'], $userTheme->spacing) : $defaultState['spacing'],
                    'brand' => $userTheme->brand ? array_merge($defaultState['brand'], $userTheme->brand) : $defaultState['brand'],
                    'custom_css' => $userTheme->custom_css ?? '',
                ];
            }
        }

        // Fallback to session for global mode
        $sessionState = Session::get('filament_theme_builder_state');
        if ($sessionState && is_array($sessionState)) {
            return array_merge($defaultState, $sessionState);
        }

        return $defaultState;
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
            // First try session
            $css = Session::get('filament_custom_css');
            if ($css) {
                $this->customCss = $css;
                return $this->customCss;
            }
            
            // Fallback to cached branding file (for login page when not authenticated)
            $brandingCssPath = storage_path('app/filament-theme-branding.css');
            if (file_exists($brandingCssPath)) {
                $this->customCss = file_get_contents($brandingCssPath);
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

    public function getColorHistory(): array
    {
        $plugin = $this->getPlugin();

        if ($plugin?->isUserMode() && auth()->check()) {
            $userTheme = UserTheme::where('user_id', auth()->id())
                ->where('panel_id', Filament::getCurrentPanel()?->getId())
                ->first();

            return $userTheme?->color_history ?? [];
        }

        return Session::get('filament_color_history', []);
    }

    public function addToColorHistory(string $color): void
    {
        $history = $this->getColorHistory();
        
        // Remove if already exists (to move to front)
        $history = array_filter($history, fn($c) => $c !== $color);
        
        // Add to beginning
        array_unshift($history, $color);
        
        // Keep only last 20 colors
        $history = array_slice($history, 0, 20);

        $plugin = $this->getPlugin();

        if ($plugin?->isUserMode() && auth()->check()) {
            UserTheme::where('user_id', auth()->id())
                ->where('panel_id', Filament::getCurrentPanel()?->getId())
                ->update(['color_history' => $history]);
        } else {
            Session::put('filament_color_history', $history);
        }
    }

    public function getFavoriteColors(): array
    {
        $plugin = $this->getPlugin();

        if ($plugin?->isUserMode() && auth()->check()) {
            $userTheme = UserTheme::where('user_id', auth()->id())
                ->where('panel_id', Filament::getCurrentPanel()?->getId())
                ->first();

            return $userTheme?->favorite_colors ?? [];
        }

        return Session::get('filament_favorite_colors', []);
    }

    public function toggleFavoriteColor(string $color): bool
    {
        $favorites = $this->getFavoriteColors();
        
        if (in_array($color, $favorites)) {
            $favorites = array_filter($favorites, fn($c) => $c !== $color);
            $isFavorite = false;
        } else {
            $favorites[] = $color;
            $isFavorite = true;
        }

        $plugin = $this->getPlugin();

        if ($plugin?->isUserMode() && auth()->check()) {
            UserTheme::where('user_id', auth()->id())
                ->where('panel_id', Filament::getCurrentPanel()?->getId())
                ->update(['favorite_colors' => array_values($favorites)]);
        } else {
            Session::put('filament_favorite_colors', array_values($favorites));
        }

        return $isFavorite;
    }

    public function duplicateTheme(?string $newName = null): array
    {
        $currentTheme = $this->exportTheme();
        $currentTheme['duplicated_from'] = $currentTheme['theme'];
        $currentTheme['duplicated_at'] = now()->toIso8601String();
        
        if ($newName) {
            $currentTheme['custom_name'] = $newName;
        }
        
        return $currentTheme;
    }

    public function getScheduledDarkModeSettings(): array
    {
        $plugin = $this->getPlugin();

        if ($plugin?->isUserMode() && auth()->check()) {
            $userTheme = UserTheme::where('user_id', auth()->id())
                ->where('panel_id', Filament::getCurrentPanel()?->getId())
                ->first();

            return $userTheme?->scheduled_dark_mode ?? [
                'enabled' => false,
                'start_time' => '18:00',
                'end_time' => '06:00',
                'use_sunset' => false,
                'timezone' => config('app.timezone', 'UTC'),
            ];
        }

        return Session::get('filament_scheduled_dark_mode', [
            'enabled' => false,
            'start_time' => '18:00',
            'end_time' => '06:00',
            'use_sunset' => false,
            'timezone' => config('app.timezone', 'UTC'),
        ]);
    }

    public function setScheduledDarkMode(array $settings): void
    {
        $plugin = $this->getPlugin();

        if ($plugin?->isUserMode() && auth()->check()) {
            UserTheme::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'panel_id' => Filament::getCurrentPanel()?->getId(),
                ],
                ['scheduled_dark_mode' => $settings]
            );
        } else {
            Session::put('filament_scheduled_dark_mode', $settings);
        }
    }

    public function shouldUseDarkModeNow(): bool
    {
        $darkMode = $this->getDarkMode();
        
        // If explicit light or dark, return accordingly
        if ($darkMode === 'light') {
            return false;
        }
        if ($darkMode === 'dark') {
            return true;
        }
        
        // Check scheduled dark mode
        $schedule = $this->getScheduledDarkModeSettings();
        
        if ($schedule['enabled'] ?? false) {
            $timezone = $schedule['timezone'] ?? config('app.timezone', 'UTC');
            $now = now($timezone);
            
            $startTime = \Carbon\Carbon::parse($schedule['start_time'], $timezone);
            $endTime = \Carbon\Carbon::parse($schedule['end_time'], $timezone);
            
            // Handle overnight schedule (e.g., 18:00 to 06:00)
            if ($startTime > $endTime) {
                // Dark mode is active if current time is after start OR before end
                return $now->gte($startTime) || $now->lt($endTime);
            } else {
                // Dark mode is active if current time is between start and end
                return $now->gte($startTime) && $now->lt($endTime);
            }
        }
        
        // Default to system preference (handled by frontend)
        return false;
    }

    public function isScheduledDarkModeEnabled(): bool
    {
        return config('filament-theme-switcher.dark_mode.scheduled', false);
    }
}
