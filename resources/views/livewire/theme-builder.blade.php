<div class="theme-builder">
    <div class="flex flex-col lg:flex-row gap-6">
        {{-- Controls Panel --}}
        <div class="w-full lg:w-1/3 space-y-6">
            {{-- Header with Undo/Redo --}}
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ __('filament-theme-switcher::theme-switcher.theme_builder') }}
                </h2>
                <div class="flex items-center gap-2">
                    <button 
                        wire:click="undo" 
                        @disabled(!$this->canUndo())
                        class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 disabled:opacity-50"
                        title="{{ __('filament-theme-switcher::theme-switcher.undo') }}"
                    >
                        <x-heroicon-o-arrow-uturn-left class="w-5 h-5" />
                    </button>
                    <button 
                        wire:click="redo" 
                        @disabled(!$this->canRedo())
                        class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 disabled:opacity-50"
                        title="{{ __('filament-theme-switcher::theme-switcher.redo') }}"
                    >
                        <x-heroicon-o-arrow-uturn-right class="w-5 h-5" />
                    </button>
                </div>
            </div>

            {{-- Color Palette Section --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                    {{ __('filament-theme-switcher::theme-switcher.colors') }}
                </h3>
                
                {{-- Palette Generator --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('filament-theme-switcher::theme-switcher.generate_palette') }}
                    </label>
                    <div class="flex gap-2 flex-wrap">
                        <button wire:click="generatePalette('complementary')" class="px-3 py-1 text-xs rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-gray-200">
                            {{ __('filament-theme-switcher::theme-switcher.palette_complementary') }}
                        </button>
                        <button wire:click="generatePalette('analogous')" class="px-3 py-1 text-xs rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-gray-200">
                            {{ __('filament-theme-switcher::theme-switcher.palette_analogous') }}
                        </button>
                        <button wire:click="generatePalette('triadic')" class="px-3 py-1 text-xs rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-gray-200">
                            {{ __('filament-theme-switcher::theme-switcher.palette_triadic') }}
                        </button>
                    </div>
                </div>

                {{-- Color Pickers --}}
                <div class="grid grid-cols-2 gap-4">
                    @foreach(['primary', 'danger', 'success', 'warning', 'info', 'gray'] as $slot)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 capitalize">
                                {{ __('filament-theme-switcher::theme-switcher.colors.' . $slot) }}
                            </label>
                            <div class="flex items-center gap-2">
                                <input 
                                    type="color" 
                                    wire:model.live.debounce.300ms="colors.{{ $slot }}"
                                    wire:change="updateColor('{{ $slot }}', $event.target.value)"
                                    class="w-10 h-10 rounded-lg cursor-pointer border-0"
                                >
                                <input 
                                    type="text" 
                                    wire:model.live.debounce.500ms="colors.{{ $slot }}"
                                    class="flex-1 text-sm px-2 py-1 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                                    placeholder="#000000"
                                >
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Component Styling Section --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                    {{ __('filament-theme-switcher::theme-switcher.component_styles') }}
                </h3>

                {{-- Sidebar --}}
                <div class="mb-4">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('filament-theme-switcher::theme-switcher.sidebar') }}
                    </h4>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="text-xs text-gray-500">{{ __('filament-theme-switcher::theme-switcher.background') }}</label>
                            <input type="color" wire:model.live="components.sidebar.background" class="w-full h-8 rounded cursor-pointer">
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">{{ __('filament-theme-switcher::theme-switcher.border_radius') }}</label>
                            <input type="range" min="0" max="16" wire:model.live="components.sidebar.border_radius" class="w-full">
                        </div>
                    </div>
                </div>

                {{-- Cards --}}
                <div class="mb-4">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('filament-theme-switcher::theme-switcher.cards') }}
                    </h4>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="text-xs text-gray-500">{{ __('filament-theme-switcher::theme-switcher.border_radius') }}</label>
                            <input type="range" min="0" max="24" wire:model.live="components.cards.border_radius" class="w-full">
                            <span class="text-xs text-gray-400">{{ $components['cards']['border_radius'] }}px</span>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">{{ __('filament-theme-switcher::theme-switcher.shadow') }}</label>
                            <select wire:model.live="components.cards.shadow" class="w-full text-sm rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800">
                                <option value="none">None</option>
                                <option value="sm">Small</option>
                                <option value="md">Medium</option>
                                <option value="lg">Large</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div>
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('filament-theme-switcher::theme-switcher.buttons') }}
                    </h4>
                    <div>
                        <label class="text-xs text-gray-500">{{ __('filament-theme-switcher::theme-switcher.border_radius') }}</label>
                        <input type="range" min="0" max="9999" wire:model.live="components.buttons.border_radius" class="w-full">
                        <span class="text-xs text-gray-400">{{ $components['buttons']['border_radius'] == '9999' ? 'Pill' : $components['buttons']['border_radius'] . 'px' }}</span>
                    </div>
                </div>
            </div>

            {{-- Spacing Section --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                    {{ __('filament-theme-switcher::theme-switcher.spacing') }}
                </h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-700 dark:text-gray-300">
                            {{ __('filament-theme-switcher::theme-switcher.sidebar_width') }}: {{ $spacing['sidebar_width'] }}px
                        </label>
                        <input type="range" min="200" max="400" wire:model.live="spacing.sidebar_width" class="w-full">
                    </div>
                    <div>
                        <label class="text-sm text-gray-700 dark:text-gray-300">
                            {{ __('filament-theme-switcher::theme-switcher.content_padding') }}: {{ $spacing['content_padding'] }}px
                        </label>
                        <input type="range" min="8" max="32" wire:model.live="spacing.content_padding" class="w-full">
                    </div>
                </div>
            </div>

            {{-- Branding Section --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                    {{ __('filament-theme-switcher::theme-switcher.branding') }}
                </h3>

                <div class="space-y-4">
                    {{-- Login Style --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('filament-theme-switcher::theme-switcher.login_style') }}
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach(['centered', 'split', 'fullscreen', 'gradient'] as $style)
                                <button
                                    wire:click="$set('brand.login_style', '{{ $style }}')"
                                    class="p-3 rounded-lg border text-sm text-left transition {{ $brand['login_style'] === $style ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300' }}"
                                >
                                    {{ __('filament-theme-switcher::theme-switcher.login_' . $style) }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Show App Name Toggle --}}
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('filament-theme-switcher::theme-switcher.show_app_name') }}
                        </label>
                        <button
                            wire:click="$toggle('brand.show_app_name')"
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition {{ $brand['show_app_name'] ? 'bg-primary-600' : 'bg-gray-200 dark:bg-gray-700' }}"
                        >
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition {{ $brand['show_app_name'] ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                    </div>

                    {{-- Brand Presets --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('filament-theme-switcher::theme-switcher.brand_presets') }}
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            @php $presets = \Isura\FilamentThemeSwitcher\Support\BrandManager::getPresets(); @endphp
                            @foreach($presets as $key => $preset)
                                <button
                                    wire:click="applyBrandPreset('{{ $key }}')"
                                    class="p-2 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-primary-500 text-left transition"
                                >
                                    <span class="text-sm font-medium">{{ $preset['name'] }}</span>
                                    <p class="text-xs text-gray-500">{{ $preset['description'] }}</p>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Typography Section --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                    {{ __('filament-theme-switcher::theme-switcher.typography') }}
                </h3>
                
                @php
                    $availableFonts = \Isura\FilamentThemeSwitcher\Support\FontManager::getSansSerifFonts();
                    $monoFonts = \Isura\FilamentThemeSwitcher\Support\FontManager::getMonospaceFonts();
                    $fontSizes = \Isura\FilamentThemeSwitcher\Support\FontManager::getFontSizes();
                @endphp

                <div class="space-y-4">
                    {{-- Heading Font --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('filament-theme-switcher::theme-switcher.heading_font') }}
                        </label>
                        <select wire:model.live="fonts.heading.family" class="w-full text-sm rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 p-2">
                            @foreach($availableFonts as $name => $data)
                                <option value="{{ $name }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <div class="mt-2 p-3 bg-gray-50 dark:bg-gray-800 rounded" style="font-family: '{{ $fonts['heading']['family'] }}', sans-serif; font-weight: {{ $fonts['heading']['weight'] }};">
                            <span class="text-lg">{{ __('filament-theme-switcher::theme-switcher.font_preview_text') }}</span>
                        </div>
                    </div>

                    {{-- Body Font --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('filament-theme-switcher::theme-switcher.body_font') }}
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <select wire:model.live="fonts.body.family" class="w-full text-sm rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 p-2">
                                @foreach($availableFonts as $name => $data)
                                    <option value="{{ $name }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <select wire:model.live="fonts.body.size" class="w-full text-sm rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 p-2">
                                @foreach($fontSizes as $key => $size)
                                    <option value="{{ $key }}">{{ $size['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2 p-3 bg-gray-50 dark:bg-gray-800 rounded" style="font-family: '{{ $fonts['body']['family'] }}', sans-serif;">
                            <span>{{ __('filament-theme-switcher::theme-switcher.font_preview_text') }}</span>
                        </div>
                    </div>

                    {{-- Mono Font --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('filament-theme-switcher::theme-switcher.mono_font') }}
                        </label>
                        <select wire:model.live="fonts.mono.family" class="w-full text-sm rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 p-2">
                            @foreach($monoFonts as $name => $data)
                                <option value="{{ $name }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <div class="mt-2 p-3 bg-gray-50 dark:bg-gray-800 rounded font-mono" style="font-family: '{{ $fonts['mono']['family'] }}', monospace;">
                            <code>const theme = "awesome";</code>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3">
                <button 
                    wire:click="applyTheme"
                    class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition"
                >
                    {{ __('filament-theme-switcher::theme-switcher.apply_theme') }}
                </button>
                <button 
                    wire:click="resetToDefault"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 transition"
                >
                    {{ __('filament-theme-switcher::theme-switcher.reset') }}
                </button>
            </div>
        </div>

        {{-- Preview Panel --}}
        <div class="w-full lg:w-2/3">
            <div class="sticky top-4">
                {{-- Preview Mode Toggle --}}
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ __('filament-theme-switcher::theme-switcher.live_preview') }}
                    </h3>
                    <div class="flex items-center gap-2 bg-gray-100 dark:bg-gray-800 rounded-lg p-1">
                        <button 
                            wire:click="setPreviewMode('desktop')"
                            class="p-2 rounded {{ $previewMode === 'desktop' ? 'bg-white dark:bg-gray-700 shadow' : '' }}"
                            title="Desktop"
                        >
                            <x-heroicon-o-computer-desktop class="w-5 h-5" />
                        </button>
                        <button 
                            wire:click="setPreviewMode('tablet')"
                            class="p-2 rounded {{ $previewMode === 'tablet' ? 'bg-white dark:bg-gray-700 shadow' : '' }}"
                            title="Tablet"
                        >
                            <x-heroicon-o-device-tablet class="w-5 h-5" />
                        </button>
                        <button 
                            wire:click="setPreviewMode('mobile')"
                            class="p-2 rounded {{ $previewMode === 'mobile' ? 'bg-white dark:bg-gray-700 shadow' : '' }}"
                            title="Mobile"
                        >
                            <x-heroicon-o-device-phone-mobile class="w-5 h-5" />
                        </button>
                    </div>
                </div>

                {{-- Preview Frame --}}
                <div class="bg-gray-100 dark:bg-gray-800 rounded-xl p-4 overflow-hidden">
                    <div 
                        class="bg-white dark:bg-gray-900 rounded-lg shadow-xl overflow-hidden transition-all duration-300 mx-auto"
                        style="
                            width: {{ $previewMode === 'mobile' ? '375px' : ($previewMode === 'tablet' ? '768px' : '100%') }};
                            min-height: 500px;
                        "
                    >
                        {{-- Mock Panel Preview --}}
                        <div class="flex h-full" style="min-height: 500px;">
                            {{-- Sidebar Preview --}}
                            <div 
                                class="w-16 lg:w-64 flex-shrink-0 p-3"
                                style="
                                    background: {{ $components['sidebar']['background'] ?: 'rgb(var(--gray-50))' }};
                                    {{ $previewMode === 'mobile' ? 'display: none;' : '' }}
                                "
                            >
                                <div class="space-y-2">
                                    <div 
                                        class="h-8 rounded flex items-center px-3 text-sm"
                                        style="
                                            background: {{ $colors['primary'] }};
                                            color: white;
                                            border-radius: {{ $components['sidebar']['border_radius'] }}px;
                                        "
                                    >
                                        <span class="hidden lg:inline">Dashboard</span>
                                    </div>
                                    @foreach(['Users', 'Posts', 'Settings'] as $item)
                                        <div 
                                            class="h-8 rounded flex items-center px-3 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800"
                                            style="border-radius: {{ $components['sidebar']['border_radius'] }}px;"
                                        >
                                            <span class="hidden lg:inline">{{ $item }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Main Content --}}
                            <div class="flex-1 p-4 space-y-4">
                                {{-- Header --}}
                                <div 
                                    class="h-12 rounded-lg flex items-center justify-between px-4"
                                    style="
                                        background: {{ $components['header']['background'] ?: 'rgb(var(--gray-100))' }};
                                    "
                                >
                                    <span class="font-medium">Preview Panel</span>
                                    <div class="flex gap-2">
                                        <div class="w-8 h-8 rounded-full" style="background: {{ $colors['gray'] }};"></div>
                                    </div>
                                </div>

                                {{-- Cards --}}
                                <div class="grid grid-cols-2 gap-4">
                                    @foreach(['primary', 'success', 'warning', 'danger'] as $color)
                                        <div 
                                            class="p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700"
                                            style="
                                                border-radius: {{ $components['cards']['border_radius'] }}px;
                                                box-shadow: {{ $components['cards']['shadow'] === 'lg' ? '0 10px 15px -3px rgba(0,0,0,0.1)' : ($components['cards']['shadow'] === 'md' ? '0 4px 6px -1px rgba(0,0,0,0.1)' : ($components['cards']['shadow'] === 'sm' ? '0 1px 2px 0 rgba(0,0,0,0.05)' : 'none')) }};
                                            "
                                        >
                                            <div 
                                                class="w-8 h-8 rounded-full mb-2"
                                                style="background: {{ $colors[$color] }};"
                                            ></div>
                                            <span class="text-sm font-medium capitalize">{{ $color }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Buttons Preview --}}
                                <div class="flex gap-3 flex-wrap">
                                    <button 
                                        style="
                                            background: {{ $colors['primary'] }};
                                            border-radius: {{ $components['buttons']['border_radius'] }}px;
                                        "
                                        class="px-4 py-2 text-white text-sm"
                                    >
                                        Primary
                                    </button>
                                    <button 
                                        style="
                                            background: {{ $colors['success'] }};
                                            border-radius: {{ $components['buttons']['border_radius'] }}px;
                                        "
                                        class="px-4 py-2 text-white text-sm"
                                    >
                                        Success
                                    </button>
                                    <button 
                                        style="
                                            background: {{ $colors['danger'] }};
                                            border-radius: {{ $components['buttons']['border_radius'] }}px;
                                        "
                                        class="px-4 py-2 text-white text-sm"
                                    >
                                        Danger
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
