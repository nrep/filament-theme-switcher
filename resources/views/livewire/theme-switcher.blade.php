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
