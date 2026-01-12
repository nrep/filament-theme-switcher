<?php

namespace Isura\FilamentThemeSwitcher\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTheme extends Model
{
    protected $table = 'user_themes';

    protected $fillable = [
        'user_id',
        'panel_id',
        'theme',
        'colors',
        'dark_mode',
        'custom_css',
        'fonts',
        'components',
        'spacing',
        'brand',
        'color_history',
        'favorite_colors',
        'scheduled_dark_mode',
    ];

    protected $casts = [
        'colors' => 'array',
        'fonts' => 'array',
        'components' => 'array',
        'spacing' => 'array',
        'brand' => 'array',
        'color_history' => 'array',
        'favorite_colors' => 'array',
        'scheduled_dark_mode' => 'array',
    ];

    protected $attributes = [
        'dark_mode' => 'system',
    ];

    public function user(): BelongsTo
    {
        $userModel = config('auth.providers.users.model', 'App\\Models\\User');

        return $this->belongsTo($userModel);
    }
}
