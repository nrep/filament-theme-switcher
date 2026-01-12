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
    ];

    protected $casts = [
        'colors' => 'array',
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
