<?php

namespace Isura\FilamentThemeSwitcher\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Isura\FilamentThemeSwitcher\FilamentThemeSwitcherPlugin;
use Isura\FilamentThemeSwitcher\Support\ColorPaletteGenerator;
use Isura\FilamentThemeSwitcher\Support\CssSnippets;
use Isura\FilamentThemeSwitcher\ThemeManager;

class ThemeSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';

    protected static string $view = 'filament-theme-switcher::pages.theme-settings';

    public ?array $data = [];

    public static function getNavigationLabel(): string
    {
        return FilamentThemeSwitcherPlugin::get()->getNavigationLabel();
    }

    public static function getNavigationGroup(): ?string
    {
        return FilamentThemeSwitcherPlugin::get()->getNavigationGroup();
    }

    public static function getNavigationSort(): ?int
    {
        return FilamentThemeSwitcherPlugin::get()->getNavigationSort();
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentThemeSwitcherPlugin::get()->getNavigationIcon();
    }

    public static function shouldRegisterNavigation(): bool
    {
        $plugin = FilamentThemeSwitcherPlugin::get();

        return $plugin->shouldRegisterNavigation() && $plugin->userCanViewThemesPage();
    }

    public function getTitle(): string
    {
        return __('filament-theme-switcher::theme-switcher.page_title');
    }

    public static function getSlug(): string
    {
        return 'theme-settings';
    }

    public function mount(): void
    {
        $themeManager = app(ThemeManager::class);

        $scheduledSettings = $themeManager->getScheduledDarkModeSettings();
        
        $this->form->fill([
            'theme' => $themeManager->getCurrentTheme(),
            'colors' => $themeManager->getCurrentColors() ?? [],
            'dark_mode' => $themeManager->getDarkMode(),
            'custom_css' => $themeManager->getCustomCss() ?? '',
            'scheduled_enabled' => $scheduledSettings['enabled'] ?? false,
            'scheduled_start' => $scheduledSettings['start_time'] ?? '18:00',
            'scheduled_end' => $scheduledSettings['end_time'] ?? '06:00',
        ]);
    }

    public function form(Form $form): Form
    {
        $themeManager = app(ThemeManager::class);
        $themes = $themeManager->getAvailableThemes();

        $themeOptions = [];
        foreach ($themes as $key => $theme) {
            $themeOptions[$key] = $theme['label'];
        }

        return $form
            ->schema([
                Section::make(__('filament-theme-switcher::theme-switcher.select_theme'))
                    ->description(__('filament-theme-switcher::theme-switcher.select_theme_description'))
                    ->schema([
                        Select::make('theme')
                            ->label(__('filament-theme-switcher::theme-switcher.theme'))
                            ->options($themeOptions)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                $themeManager = app(ThemeManager::class);
                                $theme = $themeManager->getThemeInstance($state);

                                if ($theme) {
                                    $this->data['colors'] = [];
                                }
                            }),
                    ]),

                Section::make(__('filament-theme-switcher::theme-switcher.customize_colors'))
                    ->description(__('filament-theme-switcher::theme-switcher.customize_colors_description'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                ColorPicker::make('colors.primary')
                                    ->label(__('filament-theme-switcher::theme-switcher.colors.primary'))
                                    ->live(debounce: 500),
                                Select::make('palette_type')
                                    ->label(__('filament-theme-switcher::theme-switcher.generate_palette'))
                                    ->options([
                                        'complementary' => __('filament-theme-switcher::theme-switcher.palette_complementary'),
                                        'analogous' => __('filament-theme-switcher::theme-switcher.palette_analogous'),
                                        'triadic' => __('filament-theme-switcher::theme-switcher.palette_triadic'),
                                        'split' => __('filament-theme-switcher::theme-switcher.palette_split'),
                                    ])
                                    ->placeholder(__('filament-theme-switcher::theme-switcher.palette_placeholder'))
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        $primary = $get('colors.primary');
                                        if (!$state || !$primary) return;
                                        
                                        $palette = match($state) {
                                            'complementary' => ColorPaletteGenerator::complementary($primary),
                                            'analogous' => ColorPaletteGenerator::analogous($primary),
                                            'triadic' => ColorPaletteGenerator::triadic($primary),
                                            'split' => ColorPaletteGenerator::splitComplementary($primary),
                                            default => [],
                                        };
                                        
                                        if (!empty($palette)) {
                                            // Apply generated colors
                                            if (isset($palette['complementary'])) {
                                                $set('colors.danger', $palette['complementary']);
                                            }
                                            if (isset($palette['analogous_left'])) {
                                                $set('colors.info', $palette['analogous_left']);
                                            }
                                            if (isset($palette['analogous_right'])) {
                                                $set('colors.success', $palette['analogous_right']);
                                            }
                                            if (isset($palette['triadic_1'])) {
                                                $set('colors.warning', $palette['triadic_1']);
                                            }
                                            if (isset($palette['triadic_2'])) {
                                                $set('colors.info', $palette['triadic_2']);
                                            }
                                            if (isset($palette['split_1'])) {
                                                $set('colors.success', $palette['split_1']);
                                            }
                                            if (isset($palette['split_2'])) {
                                                $set('colors.warning', $palette['split_2']);
                                            }
                                        }
                                        $set('palette_type', null);
                                    }),
                            ]),
                        Grid::make(3)
                            ->schema([
                                ColorPicker::make('colors.danger')
                                    ->label(__('filament-theme-switcher::theme-switcher.colors.danger')),
                                ColorPicker::make('colors.success')
                                    ->label(__('filament-theme-switcher::theme-switcher.colors.success')),
                                ColorPicker::make('colors.warning')
                                    ->label(__('filament-theme-switcher::theme-switcher.colors.warning')),
                                ColorPicker::make('colors.info')
                                    ->label(__('filament-theme-switcher::theme-switcher.colors.info')),
                                ColorPicker::make('colors.gray')
                                    ->label(__('filament-theme-switcher::theme-switcher.colors.gray')),
                            ]),
                    ])
                    ->collapsible(),

                Section::make(__('filament-theme-switcher::theme-switcher.dark_mode'))
                    ->description(__('filament-theme-switcher::theme-switcher.dark_mode_description'))
                    ->schema([
                        ToggleButtons::make('dark_mode')
                            ->label(__('filament-theme-switcher::theme-switcher.dark_mode_preference'))
                            ->options([
                                'light' => __('filament-theme-switcher::theme-switcher.dark_mode_light'),
                                'dark' => __('filament-theme-switcher::theme-switcher.dark_mode_dark'),
                                'system' => __('filament-theme-switcher::theme-switcher.dark_mode_system'),
                            ])
                            ->icons([
                                'light' => 'heroicon-o-sun',
                                'dark' => 'heroicon-o-moon',
                                'system' => 'heroicon-o-computer-desktop',
                            ])
                            ->inline()
                            ->default('system'),
                        Toggle::make('scheduled_enabled')
                            ->label(__('filament-theme-switcher::theme-switcher.scheduled_dark_mode'))
                            ->helperText(__('filament-theme-switcher::theme-switcher.scheduled_dark_mode_description'))
                            ->live()
                            ->visible(fn () => $themeManager->isScheduledDarkModeEnabled()),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('scheduled_start')
                                    ->label(__('filament-theme-switcher::theme-switcher.scheduled_start'))
                                    ->type('time')
                                    ->default('18:00'),
                                TextInput::make('scheduled_end')
                                    ->label(__('filament-theme-switcher::theme-switcher.scheduled_end'))
                                    ->type('time')
                                    ->default('06:00'),
                            ])
                            ->visible(fn ($get) => $get('scheduled_enabled') && $themeManager->isScheduledDarkModeEnabled()),
                    ])
                    ->visible(fn () => $themeManager->isDarkModeEnabled())
                    ->collapsible(),

                Section::make(__('filament-theme-switcher::theme-switcher.custom_css'))
                    ->description(__('filament-theme-switcher::theme-switcher.custom_css_description'))
                    ->schema([
                        Select::make('css_snippet')
                            ->label(__('filament-theme-switcher::theme-switcher.css_snippets'))
                            ->options(collect(CssSnippets::flat())->mapWithKeys(fn ($s) => [$s['name'] => $s['name'] . ' - ' . $s['description']]))
                            ->placeholder(__('filament-theme-switcher::theme-switcher.css_snippets_placeholder'))
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                if ($state) {
                                    $snippets = CssSnippets::flat();
                                    $snippet = collect($snippets)->firstWhere('name', $state);
                                    if ($snippet) {
                                        $currentCss = $get('custom_css') ?? '';
                                        $newCss = $currentCss ? $currentCss . "\n\n" . $snippet['css'] : $snippet['css'];
                                        $set('custom_css', $newCss);
                                        $set('css_snippet', null);
                                    }
                                }
                            }),
                        Textarea::make('custom_css')
                            ->label(__('filament-theme-switcher::theme-switcher.custom_css_label'))
                            ->placeholder('.fi-sidebar { background: #1a1a2e; }')
                            ->rows(10)
                            ->maxLength(config('filament-theme-switcher.custom_css.max_length', 10000)),
                    ])
                    ->visible(fn () => $themeManager->isCustomCssEnabled())
                    ->collapsible()
                    ->collapsed(),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-theme-switcher::theme-switcher.save'))
                ->submit('save'),
            Action::make('reset')
                ->label(__('filament-theme-switcher::theme-switcher.reset'))
                ->color('gray')
                ->action('resetTheme'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $themeManager = app(ThemeManager::class);

        $colors = array_filter($data['colors'] ?? []);

        $themeManager->setTheme(
            $data['theme'],
            !empty($colors) ? $colors : null,
            $data['dark_mode'] ?? 'system',
            $data['custom_css'] ?? null
        );

        // Save scheduled dark mode settings
        if ($themeManager->isScheduledDarkModeEnabled()) {
            $themeManager->setScheduledDarkMode([
                'enabled' => $data['scheduled_enabled'] ?? false,
                'start_time' => $data['scheduled_start'] ?? '18:00',
                'end_time' => $data['scheduled_end'] ?? '06:00',
                'timezone' => config('app.timezone', 'UTC'),
            ]);
        }

        Notification::make()
            ->title(__('filament-theme-switcher::theme-switcher.saved'))
            ->success()
            ->send();

        $this->redirect(request()->header('Referer'));
    }

    public function resetTheme(): void
    {
        $themeManager = app(ThemeManager::class);
        $themeManager->setTheme('default', null, 'system', null);

        $this->form->fill([
            'theme' => 'default',
            'colors' => [],
            'dark_mode' => 'system',
            'custom_css' => '',
        ]);

        Notification::make()
            ->title(__('filament-theme-switcher::theme-switcher.reset_success'))
            ->success()
            ->send();

        $this->redirect(request()->header('Referer'));
    }

    public function exportTheme(): void
    {
        $themeManager = app(ThemeManager::class);
        $data = $themeManager->exportTheme();

        $this->dispatch('download-theme', json: json_encode($data, JSON_PRETTY_PRINT));

        Notification::make()
            ->title(__('filament-theme-switcher::theme-switcher.exported'))
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        $themeManager = app(ThemeManager::class);

        return [
            Action::make('duplicate')
                ->label(__('filament-theme-switcher::theme-switcher.duplicate'))
                ->icon('heroicon-o-document-duplicate')
                ->color('gray')
                ->action('duplicateTheme')
                ->visible(fn () => $themeManager->isImportExportEnabled()),
            Action::make('export')
                ->label(__('filament-theme-switcher::theme-switcher.export'))
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action('exportTheme')
                ->visible(fn () => $themeManager->isImportExportEnabled()),
        ];
    }

    public function duplicateTheme(): void
    {
        $themeManager = app(ThemeManager::class);
        $data = $themeManager->duplicateTheme('My Custom Theme');

        $this->dispatch('download-theme', json: json_encode($data, JSON_PRETTY_PRINT));

        Notification::make()
            ->title(__('filament-theme-switcher::theme-switcher.duplicated'))
            ->success()
            ->send();
    }
}
