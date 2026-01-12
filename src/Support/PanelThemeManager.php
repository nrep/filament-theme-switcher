<?php

namespace Isura\FilamentThemeSwitcher\Support;

use Filament\Facades\Filament;

class PanelThemeManager
{
    protected static array $panelThemes = [];

    protected static array $panelColorOverrides = [];

    protected static ?string $parentPanel = null;

    public static function setThemeForPanel(string $panelId, string $themeClass): void
    {
        self::$panelThemes[$panelId] = $themeClass;
    }

    public static function getThemeForPanel(string $panelId): ?string
    {
        return self::$panelThemes[$panelId] ?? null;
    }

    public static function setColorOverrides(string $panelId, array $colors): void
    {
        self::$panelColorOverrides[$panelId] = $colors;
    }

    public static function getColorOverrides(string $panelId): array
    {
        return self::$panelColorOverrides[$panelId] ?? [];
    }

    public static function setParentPanel(string $panelId, string $parentPanelId): void
    {
        self::$parentPanel = $parentPanelId;
    }

    public static function getInheritedTheme(string $panelId): ?string
    {
        $parentPanelId = self::$parentPanel;
        
        if ($parentPanelId && isset(self::$panelThemes[$parentPanelId])) {
            return self::$panelThemes[$parentPanelId];
        }

        return null;
    }

    public static function resolveTheme(string $panelId): ?string
    {
        // First check for panel-specific theme
        if (isset(self::$panelThemes[$panelId])) {
            return self::$panelThemes[$panelId];
        }

        // Then check for inherited theme
        $inheritedTheme = self::getInheritedTheme($panelId);
        if ($inheritedTheme) {
            return $inheritedTheme;
        }

        // Return null to use default
        return null;
    }

    public static function resolveColors(string $panelId): array
    {
        $colors = [];

        // Get inherited colors first
        $parentPanelId = self::$parentPanel;
        if ($parentPanelId && isset(self::$panelColorOverrides[$parentPanelId])) {
            $colors = self::$panelColorOverrides[$parentPanelId];
        }

        // Override with panel-specific colors
        if (isset(self::$panelColorOverrides[$panelId])) {
            $colors = array_merge($colors, self::$panelColorOverrides[$panelId]);
        }

        return $colors;
    }

    public static function getCurrentPanelId(): ?string
    {
        try {
            return Filament::getCurrentPanel()?->getId();
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getAllPanelThemes(): array
    {
        return self::$panelThemes;
    }

    public static function getAllColorOverrides(): array
    {
        return self::$panelColorOverrides;
    }

    public static function clearPanelTheme(string $panelId): void
    {
        unset(self::$panelThemes[$panelId]);
    }

    public static function clearColorOverrides(string $panelId): void
    {
        unset(self::$panelColorOverrides[$panelId]);
    }

    public static function reset(): void
    {
        self::$panelThemes = [];
        self::$panelColorOverrides = [];
        self::$parentPanel = null;
    }

    public static function hasPanelTheme(string $panelId): bool
    {
        return isset(self::$panelThemes[$panelId]);
    }

    public static function hasColorOverrides(string $panelId): bool
    {
        return isset(self::$panelColorOverrides[$panelId]) && !empty(self::$panelColorOverrides[$panelId]);
    }

    public static function exportPanelConfig(string $panelId): array
    {
        return [
            'theme' => self::$panelThemes[$panelId] ?? null,
            'colors' => self::$panelColorOverrides[$panelId] ?? [],
            'parent' => self::$parentPanel,
        ];
    }

    public static function importPanelConfig(string $panelId, array $config): void
    {
        if (isset($config['theme'])) {
            self::$panelThemes[$panelId] = $config['theme'];
        }

        if (isset($config['colors'])) {
            self::$panelColorOverrides[$panelId] = $config['colors'];
        }

        if (isset($config['parent'])) {
            self::$parentPanel = $config['parent'];
        }
    }
}
