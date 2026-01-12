<div class="theme-builder" x-data x-on:theme-applied-reload.window="window.location.reload()">
    <div class="flex flex-col lg:flex-row gap-6">
        {{-- Controls Panel --}}
        <div class="w-full lg:w-1/3 space-y-4">
            {{-- Header with Undo/Redo --}}
            <div class="flex items-center justify-between bg-white dark:bg-gray-900 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <x-heroicon-o-paint-brush class="w-6 h-6 text-primary-500" />
                    {{ __('filament-theme-switcher::theme-switcher.theme_builder') }}
                </h2>
                <div class="flex items-center gap-1">
                    <button 
                        wire:click="undo" 
                        @disabled(!$this->canUndo())
                        class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        title="{{ __('filament-theme-switcher::theme-switcher.undo') }}"
                    >
                        <x-heroicon-o-arrow-uturn-left class="w-5 h-5" />
                    </button>
                    <button 
                        wire:click="redo" 
                        @disabled(!$this->canRedo())
                        class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        title="{{ __('filament-theme-switcher::theme-switcher.redo') }}"
                    >
                        <x-heroicon-o-arrow-uturn-right class="w-5 h-5" />
                    </button>
                </div>
            </div>

            {{-- Color Palette Section --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white flex items-center gap-2">
                    <x-heroicon-o-swatch class="w-5 h-5 text-primary-500" />
                    {{ __('filament-theme-switcher::theme-switcher.colors') }}
                </h3>
                
                {{-- Palette Generator --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('filament-theme-switcher::theme-switcher.generate_palette_label') }}
                    </label>
                    <div class="flex gap-2 flex-wrap">
                        <button wire:click="generatePalette('complementary')" class="px-3 py-1.5 text-xs font-medium rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-primary-100 hover:text-primary-700 dark:hover:bg-primary-900/30 dark:hover:text-primary-400 transition-colors">
                            {{ __('filament-theme-switcher::theme-switcher.palette_complementary') }}
                        </button>
                        <button wire:click="generatePalette('analogous')" class="px-3 py-1.5 text-xs font-medium rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-primary-100 hover:text-primary-700 dark:hover:bg-primary-900/30 dark:hover:text-primary-400 transition-colors">
                            {{ __('filament-theme-switcher::theme-switcher.palette_analogous') }}
                        </button>
                        <button wire:click="generatePalette('triadic')" class="px-3 py-1.5 text-xs font-medium rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-primary-100 hover:text-primary-700 dark:hover:bg-primary-900/30 dark:hover:text-primary-400 transition-colors">
                            {{ __('filament-theme-switcher::theme-switcher.palette_triadic') }}
                        </button>
                    </div>
                </div>

                {{-- Color Pickers --}}
                @php
                    $colorLabels = [
                        'primary' => __('filament-theme-switcher::theme-switcher.color_primary'),
                        'danger' => __('filament-theme-switcher::theme-switcher.color_danger'),
                        'success' => __('filament-theme-switcher::theme-switcher.color_success'),
                        'warning' => __('filament-theme-switcher::theme-switcher.color_warning'),
                        'info' => __('filament-theme-switcher::theme-switcher.color_info'),
                        'gray' => __('filament-theme-switcher::theme-switcher.color_gray'),
                    ];
                @endphp
                <div class="grid grid-cols-2 gap-3">
                    @foreach(['primary', 'danger', 'success', 'warning', 'info', 'gray'] as $slot)
                        <div class="group">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                {{ $colorLabels[$slot] }}
                            </label>
                            <div class="flex items-center gap-2">
                                <div class="relative">
                                    <input 
                                        type="color" 
                                        wire:model.live.debounce.300ms="colors.{{ $slot }}"
                                        wire:change="updateColor('{{ $slot }}', $event.target.value)"
                                        class="w-10 h-10 rounded-lg cursor-pointer border-2 border-gray-200 dark:border-gray-600 hover:border-primary-400 transition-colors"
                                    >
                                </div>
                                <input 
                                    type="text" 
                                    wire:model.live.debounce.500ms="colors.{{ $slot }}"
                                    class="flex-1 text-sm px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all"
                                    placeholder="#000000"
                                >
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Component Styling Section --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white flex items-center gap-2">
                    <x-heroicon-o-cube class="w-5 h-5 text-primary-500" />
                    {{ __('filament-theme-switcher::theme-switcher.component_styles') }}
                </h3>

                {{-- Sidebar --}}
                <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        {{ __('filament-theme-switcher::theme-switcher.sidebar') }}
                    </h4>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 block">{{ __('filament-theme-switcher::theme-switcher.background') }}</label>
                            <input type="color" wire:model.live="components.sidebar.background" class="w-full h-9 rounded-lg cursor-pointer border border-gray-300 dark:border-gray-600">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 block">{{ __('filament-theme-switcher::theme-switcher.border_radius') }}</label>
                            <input type="range" min="0" max="16" wire:model.live="components.sidebar.border_radius" class="w-full accent-primary-500">
                        </div>
                    </div>
                </div>

                {{-- Cards --}}
                <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        {{ __('filament-theme-switcher::theme-switcher.cards') }}
                    </h4>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 block">{{ __('filament-theme-switcher::theme-switcher.border_radius') }}</label>
                            <input type="range" min="0" max="24" wire:model.live="components.cards.border_radius" class="w-full accent-primary-500">
                            <span class="text-xs text-gray-400">{{ $components['cards']['border_radius'] }}px</span>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 block">{{ __('filament-theme-switcher::theme-switcher.shadow') }}</label>
                            <select wire:model.live="components.cards.shadow" class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2">
                                <option value="none">{{ __('filament-theme-switcher::theme-switcher.shadow_none') }}</option>
                                <option value="sm">{{ __('filament-theme-switcher::theme-switcher.shadow_small') }}</option>
                                <option value="md">{{ __('filament-theme-switcher::theme-switcher.shadow_medium') }}</option>
                                <option value="lg">{{ __('filament-theme-switcher::theme-switcher.shadow_large') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        {{ __('filament-theme-switcher::theme-switcher.buttons') }}
                    </h4>
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 block">{{ __('filament-theme-switcher::theme-switcher.border_radius') }}</label>
                        <input type="range" min="0" max="9999" wire:model.live="components.buttons.border_radius" class="w-full accent-primary-500">
                        <span class="text-xs text-gray-400">{{ $components['buttons']['border_radius'] == '9999' ? 'Pill' : $components['buttons']['border_radius'] . 'px' }}</span>
                    </div>
                </div>
            </div>

            {{-- Spacing Section --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white flex items-center gap-2">
                    <x-heroicon-o-arrows-pointing-out class="w-5 h-5 text-primary-500" />
                    {{ __('filament-theme-switcher::theme-switcher.spacing') }}
                </h3>
                <div class="space-y-4">
                    <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 flex justify-between mb-2">
                            <span>{{ __('filament-theme-switcher::theme-switcher.sidebar_width') }}</span>
                            <span class="text-primary-600 dark:text-primary-400">{{ $spacing['sidebar_width'] }}px</span>
                        </label>
                        <input type="range" min="200" max="400" wire:model.live="spacing.sidebar_width" class="w-full accent-primary-500">
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 flex justify-between mb-2">
                            <span>{{ __('filament-theme-switcher::theme-switcher.content_padding') }}</span>
                            <span class="text-primary-600 dark:text-primary-400">{{ $spacing['content_padding'] }}px</span>
                        </label>
                        <input type="range" min="8" max="32" wire:model.live="spacing.content_padding" class="w-full accent-primary-500">
                    </div>
                </div>
            </div>

            {{-- Branding Section --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white flex items-center gap-2">
                    <x-heroicon-o-building-storefront class="w-5 h-5 text-primary-500" />
                    {{ __('filament-theme-switcher::theme-switcher.branding') }}
                </h3>

                <div class="space-y-4">
                    {{-- Login Style --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('filament-theme-switcher::theme-switcher.login_style') }}
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            @php
                                $loginStyles = [
                                    'centered' => __('filament-theme-switcher::theme-switcher.login_centered'),
                                    'split' => __('filament-theme-switcher::theme-switcher.login_split'),
                                    'fullscreen' => __('filament-theme-switcher::theme-switcher.login_fullscreen'),
                                    'gradient' => __('filament-theme-switcher::theme-switcher.login_gradient'),
                                ];
                            @endphp
                            @foreach($loginStyles as $style => $label)
                                <button
                                    type="button"
                                    wire:click="setLoginStyle('{{ $style }}')"
                                    class="p-3 rounded-lg border text-sm text-left transition-all {{ $brand['login_style'] === $style ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20 ring-1 ring-primary-500' : 'border-gray-200 dark:border-gray-700 hover:border-primary-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}"
                                >
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Show App Name Toggle --}}
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('filament-theme-switcher::theme-switcher.show_app_name') }}
                        </label>
                        <button
                            type="button"
                            wire:click="toggleShowAppName"
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $brand['show_app_name'] ? 'bg-primary-600' : 'bg-gray-300 dark:bg-gray-600' }}"
                        >
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition-transform {{ $brand['show_app_name'] ? 'translate-x-6' : 'translate-x-1' }}"></span>
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
                                    class="p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-primary-400 hover:bg-gray-50 dark:hover:bg-gray-800 text-left transition-all"
                                >
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $preset['name'] }}</span>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $preset['description'] }}</p>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Typography Section --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white flex items-center gap-2">
                    <x-heroicon-o-language class="w-5 h-5 text-primary-500" />
                    {{ __('filament-theme-switcher::theme-switcher.typography') }}
                </h3>

                @php
                    $availableFonts = \Isura\FilamentThemeSwitcher\Support\FontManager::getSansSerifFonts();
                    $monoFonts = \Isura\FilamentThemeSwitcher\Support\FontManager::getMonospaceFonts();
                    $fontSizes = \Isura\FilamentThemeSwitcher\Support\FontManager::getFontSizes();
                @endphp

                <div class="space-y-4">
                    {{-- Heading Font --}}
                    <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('filament-theme-switcher::theme-switcher.heading_font') }}
                        </label>
                        <select wire:model.live="fonts.heading.family" class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2">
                            @foreach($availableFonts as $name => $data)
                                <option value="{{ $name }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <div class="mt-2 p-3 bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700" style="font-family: '{{ $fonts['heading']['family'] }}', sans-serif; font-weight: {{ $fonts['heading']['weight'] }};">
                            <span class="text-lg">{{ __('filament-theme-switcher::theme-switcher.font_preview_text') }}</span>
                        </div>
                    </div>

                    {{-- Body Font --}}
                    <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('filament-theme-switcher::theme-switcher.body_font') }}
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <select wire:model.live="fonts.body.family" class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2">
                                @foreach($availableFonts as $name => $data)
                                    <option value="{{ $name }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <select wire:model.live="fonts.body.size" class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2">
                                @foreach($fontSizes as $key => $size)
                                    <option value="{{ $key }}">{{ $size['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2 p-3 bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700" style="font-family: '{{ $fonts['body']['family'] }}', sans-serif;">
                            <span>{{ __('filament-theme-switcher::theme-switcher.font_preview_text') }}</span>
                        </div>
                    </div>

                    {{-- Mono Font --}}
                    <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('filament-theme-switcher::theme-switcher.mono_font') }}
                        </label>
                        <select wire:model.live="fonts.mono.family" class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2">
                            @foreach($monoFonts as $name => $data)
                                <option value="{{ $name }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <div class="mt-2 p-3 bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 font-mono" style="font-family: '{{ $fonts['mono']['family'] }}', monospace;">
                            <code class="text-primary-600 dark:text-primary-400">const theme = "awesome";</code>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 sticky bottom-4 bg-white dark:bg-gray-900 p-4 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                <button
                    wire:click="applyTheme"
                    wire:loading.attr="disabled"
                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all disabled:opacity-50"
                >
                    <x-heroicon-o-check class="w-5 h-5" />
                    <span wire:loading.remove wire:target="applyTheme">{{ __('filament-theme-switcher::theme-switcher.apply_theme') }}</span>
                    <span wire:loading wire:target="applyTheme">{{ __('filament-theme-switcher::theme-switcher.save') }}...</span>
                </button>
                <button
                    wire:click="resetToDefault"
                    wire:confirm="{{ __('filament-theme-switcher::theme-switcher.reset_confirm') }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all"
                >
                    <x-heroicon-o-arrow-path class="w-5 h-5" />
                    {{ __('filament-theme-switcher::theme-switcher.reset') }}
                </button>
            </div>
        </div>

        {{-- Preview Panel --}}
        <div class="w-full lg:w-2/3">
            <div class="sticky top-4">
                {{-- Preview Mode Toggle --}}
                <div class="flex items-center justify-between mb-4 bg-white dark:bg-gray-900 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <x-heroicon-o-eye class="w-5 h-5 text-primary-500" />
                        {{ __('filament-theme-switcher::theme-switcher.live_preview') }}
                    </h3>
                    <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 rounded-lg p-1">
                        <button
                            wire:click="setPreviewMode('desktop')"
                            class="p-2 rounded-md transition-all {{ $previewMode === 'desktop' ? 'bg-white dark:bg-gray-700 shadow-sm text-primary-600' : 'text-gray-500 hover:text-gray-700' }}"
                            title="{{ __('filament-theme-switcher::theme-switcher.preview_desktop') }}"
                        >
                            <x-heroicon-o-computer-desktop class="w-5 h-5" />
                        </button>
                        <button
                            wire:click="setPreviewMode('tablet')"
                            class="p-2 rounded-md transition-all {{ $previewMode === 'tablet' ? 'bg-white dark:bg-gray-700 shadow-sm text-primary-600' : 'text-gray-500 hover:text-gray-700' }}"
                            title="{{ __('filament-theme-switcher::theme-switcher.preview_tablet') }}"
                        >
                            <x-heroicon-o-device-tablet class="w-5 h-5" />
                        </button>
                        <button
                            wire:click="setPreviewMode('mobile')"
                            class="p-2 rounded-md transition-all {{ $previewMode === 'mobile' ? 'bg-white dark:bg-gray-700 shadow-sm text-primary-600' : 'text-gray-500 hover:text-gray-700' }}"
                            title="{{ __('filament-theme-switcher::theme-switcher.preview_mobile') }}"
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
