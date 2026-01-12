<?php

namespace Isura\FilamentThemeSwitcher\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class TenantThemeManager
{
    protected static ?string $currentTenantId = null;

    protected static array $tenantThemes = [];

    protected static array $tenantBranding = [];

    protected static bool $whiteLabelEnabled = false;

    public static function setCurrentTenant(?string $tenantId): void
    {
        self::$currentTenantId = $tenantId;
    }

    public static function getCurrentTenant(): ?string
    {
        return self::$currentTenantId;
    }

    public static function resolveThemeForTenant(?string $tenantId = null): ?string
    {
        $tenantId = $tenantId ?? self::$currentTenantId;

        if (!$tenantId) {
            return null;
        }

        // Check cache first
        $cacheKey = "tenant_theme_{$tenantId}";
        $cached = Cache::get($cacheKey);
        
        if ($cached !== null) {
            return $cached;
        }

        // Check registered themes
        if (isset(self::$tenantThemes[$tenantId])) {
            Cache::put($cacheKey, self::$tenantThemes[$tenantId], 3600);
            return self::$tenantThemes[$tenantId];
        }

        return null;
    }

    public static function setThemeForTenant(string $tenantId, string $themeClass): void
    {
        self::$tenantThemes[$tenantId] = $themeClass;
        Cache::put("tenant_theme_{$tenantId}", $themeClass, 3600);
    }

    public static function getTenantBranding(?string $tenantId = null): array
    {
        $tenantId = $tenantId ?? self::$currentTenantId;

        if (!$tenantId) {
            return [];
        }

        return self::$tenantBranding[$tenantId] ?? [];
    }

    public static function setTenantBranding(string $tenantId, array $branding): void
    {
        self::$tenantBranding[$tenantId] = $branding;
        Cache::put("tenant_branding_{$tenantId}", $branding, 3600);
    }

    public static function resolveTenantFromSubdomain(?string $host = null): ?string
    {
        $host = $host ?? request()->getHost();
        
        // Get base domain from config
        $baseDomain = Config::get('filament-theme-switcher.tenant.base_domain');
        
        if (!$baseDomain) {
            return null;
        }

        // Extract subdomain
        if (str_ends_with($host, $baseDomain)) {
            $subdomain = str_replace(".{$baseDomain}", '', $host);
            
            if ($subdomain && $subdomain !== $baseDomain) {
                return $subdomain;
            }
        }

        return null;
    }

    public static function enableWhiteLabel(bool $enabled = true): void
    {
        self::$whiteLabelEnabled = $enabled;
    }

    public static function isWhiteLabelEnabled(): bool
    {
        return self::$whiteLabelEnabled;
    }

    public static function getWhiteLabelConfig(?string $tenantId = null): array
    {
        $tenantId = $tenantId ?? self::$currentTenantId;
        
        $branding = self::getTenantBranding($tenantId);

        return [
            'app_name' => $branding['app_name'] ?? Config::get('app.name'),
            'logo' => $branding['logo'] ?? null,
            'logo_dark' => $branding['logo_dark'] ?? null,
            'favicon' => $branding['favicon'] ?? null,
            'primary_color' => $branding['primary_color'] ?? '#3b82f6',
            'hide_powered_by' => self::$whiteLabelEnabled,
        ];
    }

    public static function clearTenantCache(string $tenantId): void
    {
        Cache::forget("tenant_theme_{$tenantId}");
        Cache::forget("tenant_branding_{$tenantId}");
    }

    public static function registerTenantTheme(string $tenantId, string $themeClass, array $branding = []): void
    {
        self::setThemeForTenant($tenantId, $themeClass);
        
        if (!empty($branding)) {
            self::setTenantBranding($tenantId, $branding);
        }
    }

    public static function getAllTenantThemes(): array
    {
        return self::$tenantThemes;
    }

    public static function getAllTenantBranding(): array
    {
        return self::$tenantBranding;
    }

    public static function hasTenantTheme(string $tenantId): bool
    {
        return isset(self::$tenantThemes[$tenantId]);
    }

    public static function removeTenantTheme(string $tenantId): void
    {
        unset(self::$tenantThemes[$tenantId]);
        unset(self::$tenantBranding[$tenantId]);
        self::clearTenantCache($tenantId);
    }

    public static function exportTenantConfig(string $tenantId): array
    {
        return [
            'tenant_id' => $tenantId,
            'theme' => self::$tenantThemes[$tenantId] ?? null,
            'branding' => self::$tenantBranding[$tenantId] ?? [],
            'white_label' => self::$whiteLabelEnabled,
        ];
    }

    public static function importTenantConfig(array $config): void
    {
        $tenantId = $config['tenant_id'] ?? null;
        
        if (!$tenantId) {
            return;
        }

        if (isset($config['theme'])) {
            self::$tenantThemes[$tenantId] = $config['theme'];
        }

        if (isset($config['branding'])) {
            self::$tenantBranding[$tenantId] = $config['branding'];
        }

        if (isset($config['white_label'])) {
            self::$whiteLabelEnabled = $config['white_label'];
        }
    }

    public static function reset(): void
    {
        self::$currentTenantId = null;
        self::$tenantThemes = [];
        self::$tenantBranding = [];
        self::$whiteLabelEnabled = false;
    }
}
