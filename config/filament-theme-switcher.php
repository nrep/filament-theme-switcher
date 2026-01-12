<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Theme Mode
    |--------------------------------------------------------------------------
    |
    | This option determines how the theme will be set for the application.
    | By default, 'global' mode is set to use one theme for all users. If you
    | want to set a theme for each user separately, then set to 'user'.
    |
    | Supported: "global", "user"
    |
    */
    'mode' => 'global',

    /*
    |--------------------------------------------------------------------------
    | Default Theme
    |--------------------------------------------------------------------------
    |
    | This is the default theme that will be applied when no theme is selected.
    |
    */
    'default_theme' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Theme Icon
    |--------------------------------------------------------------------------
    |
    | The icon to display in the navigation for the theme settings page.
    |
    */
    'icon' => 'heroicon-o-swatch',

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | Enable caching of theme settings for better performance.
    |
    */
    'cache' => [
        'enabled' => true,
        'ttl' => 3600,
    ],

    /*
    |--------------------------------------------------------------------------
    | Dark Mode
    |--------------------------------------------------------------------------
    |
    | Configure dark mode behavior. When enabled, users can toggle between
    | light and dark modes. System mode will follow the user's OS preference.
    |
    | Supported modes: "light", "dark", "system"
    |
    */
    'dark_mode' => [
        'enabled' => true,
        'default' => 'system',
        'toggle_in_header' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom CSS
    |--------------------------------------------------------------------------
    |
    | Allow users to add custom CSS to their theme.
    |
    */
    'custom_css' => [
        'enabled' => true,
        'max_length' => 10000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme Import/Export
    |--------------------------------------------------------------------------
    |
    | Allow users to import and export theme configurations.
    |
    */
    'import_export' => [
        'enabled' => true,
    ],
];
