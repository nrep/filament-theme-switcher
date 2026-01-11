<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getFormActions()"
        />
    </x-filament-panels::form>

    <div class="mt-8">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
            {{ __('filament-theme-switcher::theme-switcher.select_theme') }}
        </h3>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @php
                $themeManager = app(\Isura\FilamentThemeSwitcher\ThemeManager::class);
                $themes = $themeManager->getAvailableThemes();
                $currentTheme = $this->data['theme'] ?? 'default';
            @endphp
            
            @foreach($themes as $key => $theme)
                <button 
                    type="button"
                    wire:click="$set('data.theme', '{{ $key }}')"
                    class="relative p-4 rounded-lg border-2 transition-all duration-200 hover:shadow-md {{ $currentTheme === $key ? 'border-primary-500 ring-2 ring-primary-500/20' : 'border-gray-200 dark:border-gray-700' }}"
                >
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-4 h-4 rounded-full" style="background-color: {{ $theme['preview']['primary'] ?? '#3b82f6' }}"></div>
                        <div class="w-4 h-4 rounded-full" style="background-color: {{ $theme['preview']['secondary'] ?? '#71717a' }}"></div>
                        <div class="w-4 h-4 rounded-full" style="background-color: {{ $theme['preview']['accent'] ?? '#22c55e' }}"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $theme['label'] }}
                    </span>
                    @if($currentTheme === $key)
                        <div class="absolute top-2 right-2">
                            <x-heroicon-s-check-circle class="w-5 h-5 text-primary-500" />
                        </div>
                    @endif
                </button>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>
