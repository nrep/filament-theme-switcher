# Filament Theme Switcher

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nrep/filament-theme-switcher.svg?style=flat-square)](https://packagist.org/packages/nrep/filament-theme-switcher)
[![Total Downloads](https://img.shields.io/packagist/dt/nrep/filament-theme-switcher.svg?style=flat-square)](https://packagist.org/packages/nrep/filament-theme-switcher)

A FilamentPHP plugin that allows users to easily switch and customize application themes. Supports both global themes and per-user theme preferences.

## Features

- 🎨 **7 Pre-built Themes**: Default, Sunset, Ocean, Forest, Midnight, Rose, and Amber
- 🔄 **Easy Theme Switching**: Quick switch via dropdown or dedicated settings page
- 🎯 **Per-User Themes**: Optional per-user theme preferences with database storage
- 🖌️ **Custom Colors**: Override any theme color with custom color picker
- 🔐 **Authorization**: Control who can access theme settings
- 🌍 **Translatable**: Full translation support
- ⚡ **Filament v3 & v4 Compatible**: Works with both major versions

## Requirements

- PHP 8.1+
- Laravel 10.x, 11.x, or 12.x
- Filament 3.x or 4.x

## Installation

Install the package via composer:

```bash
composer require nrep/filament-theme-switcher
```

Publish the config file (optional):

```bash
php artisan vendor:publish --tag="filament-theme-switcher-config"
```

For per-user theme support, publish and run the migrations:

```bash
php artisan vendor:publish --tag="filament-theme-switcher-migrations"
php artisan migrate
```

## Usage

### Basic Setup

Register the plugin in your Panel provider:

```php
use Isura\FilamentThemeSwitcher\FilamentThemeSwitcherPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ... other configuration
        ->plugin(
            FilamentThemeSwitcherPlugin::make()
        );
}
```

Add the middleware to apply themes:

```php
use Isura\FilamentThemeSwitcher\Http\Middleware\SetTheme;

public function panel(Panel $panel): Panel
{
    return $panel
        // ... other configuration
        ->middleware([
            // ... other middleware
            SetTheme::class,
        ]);
}
```

### Configuration Options

#### Theme Mode

Set the theme mode in the config file or via the plugin:

```php
// config/filament-theme-switcher.php
return [
    'mode' => 'global', // or 'user' for per-user themes
    'default_theme' => 'default',
];
```

#### Plugin Configuration

```php
FilamentThemeSwitcherPlugin::make()
    // Set the theme mode
    ->mode('user') // 'global' or 'user'
    
    // Customize navigation
    ->navigationGroup('Settings')
    ->navigationIcon('heroicon-o-paint-brush')
    ->navigationSort(100)
    ->navigationLabel('Appearance')
    
    // Control page access
    ->registerNavigation(true) // Show/hide from navigation
    ->canViewThemesPage(fn () => auth()->user()?->is_admin)
    
    // Register custom themes
    ->registerThemes([
        'my-theme' => MyCustomTheme::class,
    ])
    
    // Override default themes entirely
    ->registerThemes([
        'my-theme' => MyCustomTheme::class,
    ], override: true);
```

### Creating Custom Themes

Create a new theme by extending `AbstractTheme`:

```php
<?php

namespace App\Filament\Themes;

use Filament\Support\Colors\Color;
use Isura\FilamentThemeSwitcher\Themes\AbstractTheme;

class MyCustomTheme extends AbstractTheme
{
    public static function getName(): string
    {
        return 'my-custom-theme';
    }

    public function getLabel(): string
    {
        return 'My Custom Theme';
    }

    public function getColors(): array
    {
        return [
            'primary' => Color::Purple,
            'danger' => Color::Red,
            'gray' => Color::Slate,
            'info' => Color::Blue,
            'success' => Color::Green,
            'warning' => Color::Amber,
        ];
    }
}
```

Register your custom theme:

```php
FilamentThemeSwitcherPlugin::make()
    ->registerThemes([
        'my-custom-theme' => MyCustomTheme::class,
    ]);
```

### Using Hex Colors

You can use hex colors instead of Filament's Color constants:

```php
public function getColors(): array
{
    return [
        'primary' => '#8b5cf6',
        'danger' => '#ef4444',
        'gray' => '#64748b',
        'info' => '#3b82f6',
        'success' => '#22c55e',
        'warning' => '#f59e0b',
    ];
}
```

### Theme Switcher Component

Add the theme switcher dropdown to your panel's render hooks:

```php
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;

public function panel(Panel $panel): Panel
{
    return $panel
        ->renderHook(
            PanelsRenderHook::USER_MENU_BEFORE,
            fn () => Blade::render('@livewire(\'theme-switcher\')')
        );
}
```

## Available Themes

| Theme | Primary Color | Description |
|-------|---------------|-------------|
| Default | Blue | Clean, professional blue theme |
| Sunset | Orange | Warm orange and amber tones |
| Ocean | Cyan | Cool ocean-inspired colors |
| Forest | Emerald | Natural green forest theme |
| Midnight | Indigo | Deep purple night theme |
| Rose | Rose | Soft pink and rose colors |
| Amber | Amber | Warm golden tones |

## Authorization

Control who can access the theme settings page:

```php
FilamentThemeSwitcherPlugin::make()
    ->canViewThemesPage(fn () => auth()->user()?->hasPermission('manage-themes'));
```

## Translations

Publish the language files:

```bash
php artisan vendor:publish --tag="filament-theme-switcher-translations"
```

The package includes English translations. Add your own translations in `resources/lang/vendor/filament-theme-switcher/`.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [nrep](https://github.com/nrep)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
