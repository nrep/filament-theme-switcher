<?php

namespace Isura\FilamentThemeSwitcher\Support;

use Illuminate\Support\Facades\Storage;

class BrandManager
{
    protected static array $defaultBrandKit = [
        'logo' => null,
        'logo_dark' => null,
        'favicon' => null,
        'login_background' => null,
        'login_logo' => null,
        'primary_color' => '#3b82f6',
        'accent_color' => '#6366f1',
    ];

    protected static array $presets = [
        'minimal' => [
            'name' => 'Minimal',
            'description' => 'Clean and simple branding',
            'login_style' => 'centered',
            'show_app_name' => true,
            'rounded_logo' => false,
        ],
        'corporate' => [
            'name' => 'Corporate',
            'description' => 'Professional business look',
            'login_style' => 'split',
            'show_app_name' => true,
            'rounded_logo' => false,
        ],
        'modern' => [
            'name' => 'Modern',
            'description' => 'Bold and contemporary',
            'login_style' => 'fullscreen',
            'show_app_name' => false,
            'rounded_logo' => true,
        ],
        'startup' => [
            'name' => 'Startup',
            'description' => 'Fresh and energetic',
            'login_style' => 'gradient',
            'show_app_name' => true,
            'rounded_logo' => true,
        ],
    ];

    public static function getDefaultBrandKit(): array
    {
        return self::$defaultBrandKit;
    }

    public static function getPresets(): array
    {
        return self::$presets;
    }

    public static function getPreset(string $name): ?array
    {
        return self::$presets[$name] ?? null;
    }

    public static function saveLogo(string $path, ?string $disk = null): ?string
    {
        $disk = $disk ?? config('filament-theme-switcher.brand.disk', 'public');
        
        if (!Storage::disk($disk)->exists($path)) {
            return null;
        }

        return Storage::disk($disk)->url($path);
    }

    public static function generateFaviconPath(string $filename): string
    {
        return 'brand/favicons/' . $filename;
    }

    public static function generateLogoPath(string $filename): string
    {
        return 'brand/logos/' . $filename;
    }

    public static function getLoginStyles(): array
    {
        return [
            'centered' => [
                'label' => 'Centered',
                'description' => 'Logo and form centered on page',
            ],
            'split' => [
                'label' => 'Split Screen',
                'description' => 'Image on left, form on right',
            ],
            'fullscreen' => [
                'label' => 'Fullscreen Background',
                'description' => 'Form over background image',
            ],
            'gradient' => [
                'label' => 'Gradient Background',
                'description' => 'Form over gradient',
            ],
        ];
    }

    public static function generateLoginCSS(array $brandKit): string
    {
        $css = '';
        
        $loginStyle = $brandKit['login_style'] ?? 'centered';
        $primaryColor = $brandKit['primary_color'] ?? '#3b82f6';
        $accentColor = $brandKit['accent_color'] ?? '#6366f1';

        // Login page background
        if (!empty($brandKit['login_background'])) {
            $css .= ".fi-simple-layout { background-image: url('{$brandKit['login_background']}'); background-size: cover; background-position: center; }\n";
        }

        // Gradient background
        if ($loginStyle === 'gradient') {
            $css .= ".fi-simple-layout { background: linear-gradient(135deg, {$primaryColor} 0%, {$accentColor} 100%); }\n";
        }

        // Login form styling
        $css .= ".fi-simple-main { backdrop-filter: blur(10px); }\n";

        return $css;
    }

    public static function generateFaviconHTML(?string $faviconUrl): string
    {
        if (!$faviconUrl) {
            return '';
        }

        return <<<HTML
<link rel="icon" type="image/x-icon" href="{$faviconUrl}">
<link rel="apple-touch-icon" href="{$faviconUrl}">
HTML;
    }

    public static function getEmailTemplateVariables(): array
    {
        return [
            '{{logo_url}}' => 'URL to brand logo',
            '{{primary_color}}' => 'Primary brand color',
            '{{accent_color}}' => 'Accent brand color',
            '{{app_name}}' => 'Application name',
            '{{support_email}}' => 'Support email address',
        ];
    }

    public static function processEmailTemplate(string $template, array $brandKit, array $appConfig = []): string
    {
        $replacements = [
            '{{logo_url}}' => $brandKit['logo'] ?? '',
            '{{primary_color}}' => $brandKit['primary_color'] ?? '#3b82f6',
            '{{accent_color}}' => $brandKit['accent_color'] ?? '#6366f1',
            '{{app_name}}' => $appConfig['name'] ?? config('app.name'),
            '{{support_email}}' => $appConfig['support_email'] ?? config('mail.from.address'),
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }

    public static function validateImage(string $path, string $type = 'logo'): array
    {
        $errors = [];
        
        $maxSizes = [
            'logo' => 2 * 1024 * 1024, // 2MB
            'favicon' => 512 * 1024, // 512KB
            'background' => 5 * 1024 * 1024, // 5MB
        ];

        $allowedTypes = [
            'logo' => ['image/png', 'image/jpeg', 'image/svg+xml', 'image/webp'],
            'favicon' => ['image/x-icon', 'image/png', 'image/svg+xml'],
            'background' => ['image/png', 'image/jpeg', 'image/webp'],
        ];

        $recommendedSizes = [
            'logo' => ['width' => 200, 'height' => 50],
            'favicon' => ['width' => 32, 'height' => 32],
            'background' => ['width' => 1920, 'height' => 1080],
        ];

        return $errors;
    }
}
