<?php

namespace Isura\FilamentThemeSwitcher\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Isura\FilamentThemeSwitcher\FilamentThemeSwitcherPlugin;
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

        $this->form->fill([
            'theme' => $themeManager->getCurrentTheme(),
            'colors' => $themeManager->getCurrentColors() ?? [],
            'dark_mode' => $themeManager->getDarkMode(),
            'custom_css' => $themeManager->getCustomCss() ?? '',
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
                        Grid::make(3)
                            ->schema([
                                ColorPicker::make('colors.primary')
                                    ->label(__('filament-theme-switcher::theme-switcher.colors.primary')),
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
                    ])
                    ->visible(fn () => $themeManager->isDarkModeEnabled())
                    ->collapsible(),

                Section::make(__('filament-theme-switcher::theme-switcher.custom_css'))
                    ->description(__('filament-theme-switcher::theme-switcher.custom_css_description'))
                    ->schema([
                        Textarea::make('custom_css')
                            ->label(__('filament-theme-switcher::theme-switcher.custom_css_label'))
                            ->placeholder('.fi-sidebar { background: #1a1a2e; }')
                            ->rows(8)
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
            Action::make('export')
                ->label(__('filament-theme-switcher::theme-switcher.export'))
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action('exportTheme')
                ->visible(fn () => $themeManager->isImportExportEnabled()),
        ];
    }
}
