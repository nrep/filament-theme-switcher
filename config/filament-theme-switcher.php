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
];
