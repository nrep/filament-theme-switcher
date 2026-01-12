<?php

namespace Isura\FilamentThemeSwitcher\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Isura\FilamentThemeSwitcher\Support\TenantThemeManager;
use Isura\FilamentThemeSwitcher\ThemeManager;
use Symfony\Component\HttpFoundation\Response;

class TenantThemeMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!config('filament-theme-switcher.tenant.enabled')) {
            return $next($request);
        }

        // Resolve tenant from subdomain if enabled
        if (config('filament-theme-switcher.tenant.subdomain_detection')) {
            $tenantId = TenantThemeManager::resolveTenantFromSubdomain($request->getHost());
            
            if ($tenantId) {
                TenantThemeManager::setCurrentTenant($tenantId);
            }
        }

        // Apply tenant theme if available
        $currentTenant = TenantThemeManager::getCurrentTenant();
        
        if ($currentTenant) {
            $themeClass = TenantThemeManager::resolveThemeForTenant($currentTenant);
            
            if ($themeClass) {
                $themeManager = app(ThemeManager::class);
                $themeManager->setTheme($themeClass);
            }

            // Apply tenant branding
            $branding = TenantThemeManager::getTenantBranding($currentTenant);
            
            if (!empty($branding)) {
                $this->applyTenantBranding($branding);
            }
        }

        // Handle white-label mode
        if (config('filament-theme-switcher.white_label.enabled')) {
            TenantThemeManager::enableWhiteLabel(true);
        }

        return $next($request);
    }

    protected function applyTenantBranding(array $branding): void
    {
        // Share branding with views
        view()->share('tenantBranding', $branding);

        // Override app name if provided
        if (isset($branding['app_name'])) {
            config(['app.name' => $branding['app_name']]);
        }
    }
}
