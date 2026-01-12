@php
    $themeManager = app(\Isura\FilamentThemeSwitcher\ThemeManager::class);
    $customCss = $themeManager->getCustomCss();
@endphp

@if($customCss)
<style id="filament-theme-switcher-custom-css">
{!! $customCss !!}
</style>
@endif
