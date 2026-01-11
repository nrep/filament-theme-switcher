<?php

namespace Isura\FilamentThemeSwitcher\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Isura\FilamentThemeSwitcher\ThemeManager;
use Symfony\Component\HttpFoundation\Response;

class SetTheme
{
    public function __construct(
        protected ThemeManager $themeManager
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $this->themeManager->applyTheme();

        return $next($request);
    }
}
