<div>
    <x-filament::dropdown placement="bottom-end">
        <x-slot name="trigger">
            <button
                type="button"
                class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-500/5 focus:bg-gray-500/5 dark:hover:bg-gray-400/5 dark:focus:bg-gray-400/5"
            >
                <x-heroicon-o-swatch class="w-5 h-5 text-gray-500 dark:text-gray-400" />
            </button>
        </x-slot>

        @if($darkModeEnabled)
            <x-filament::dropdown.header>
                {{ __('filament-theme-switcher::theme-switcher.dark_mode') }}
            </x-filament::dropdown.header>
            <x-filament::dropdown.list>
                <x-filament::dropdown.list.item
                    wire:click="switchDarkMode('light')"
                    :icon="$darkMode === 'light' ? 'heroicon-s-check-circle' : 'heroicon-o-sun'"
                    :color="$darkMode === 'light' ? 'primary' : 'gray'"
                >
                    {{ __('filament-theme-switcher::theme-switcher.dark_mode_light') }}
                </x-filament::dropdown.list.item>
                <x-filament::dropdown.list.item
                    wire:click="switchDarkMode('dark')"
                    :icon="$darkMode === 'dark' ? 'heroicon-s-check-circle' : 'heroicon-o-moon'"
                    :color="$darkMode === 'dark' ? 'primary' : 'gray'"
                >
                    {{ __('filament-theme-switcher::theme-switcher.dark_mode_dark') }}
                </x-filament::dropdown.list.item>
                <x-filament::dropdown.list.item
                    wire:click="switchDarkMode('system')"
                    :icon="$darkMode === 'system' ? 'heroicon-s-check-circle' : 'heroicon-o-computer-desktop'"
                    :color="$darkMode === 'system' ? 'primary' : 'gray'"
                >
                    {{ __('filament-theme-switcher::theme-switcher.dark_mode_system') }}
                </x-filament::dropdown.list.item>
            </x-filament::dropdown.list>
        @endif

        <x-filament::dropdown.header>
            {{ __('filament-theme-switcher::theme-switcher.theme') }}
        </x-filament::dropdown.header>
        <x-filament::dropdown.list>
            @foreach($themes as $key => $theme)
                <x-filament::dropdown.list.item
                    wire:click="switchTheme('{{ $key }}')"
                    :icon="$currentTheme === $key ? 'heroicon-s-check-circle' : 'heroicon-o-swatch'"
                    :color="$currentTheme === $key ? 'primary' : 'gray'"
                >
                    <div class="flex items-center gap-2">
                        <div class="flex gap-1">
                            <div class="w-3 h-3 rounded-full" style="background-color: {{ $theme['preview']['primary'] ?? '#3b82f6' }}"></div>
                            <div class="w-3 h-3 rounded-full" style="background-color: {{ $theme['preview']['secondary'] ?? '#71717a' }}"></div>
                        </div>
                        <span>{{ $theme['label'] }}</span>
                    </div>
                </x-filament::dropdown.list.item>
            @endforeach
        </x-filament::dropdown.list>

        <x-filament::dropdown.list>
            <x-filament::dropdown.list.item
                :href="route('filament.admin.pages.theme-settings')"
                icon="heroicon-o-cog-6-tooth"
            >
                {{ __('filament-theme-switcher::theme-switcher.page_title') }}
            </x-filament::dropdown.list.item>
        </x-filament::dropdown.list>
    </x-filament::dropdown>
</div>
