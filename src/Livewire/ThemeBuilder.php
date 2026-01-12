<?php

namespace Isura\FilamentThemeSwitcher\Livewire;

use Filament\Facades\Filament;
use Isura\FilamentThemeSwitcher\Support\BrandManager;
use Isura\FilamentThemeSwitcher\Support\ColorPaletteGenerator;
use Isura\FilamentThemeSwitcher\Support\FontManager;
use Isura\FilamentThemeSwitcher\ThemeManager;
use Livewire\Attributes\On;
use Livewire\Component;

class ThemeBuilder extends Component
{
    public array $colors = [
        'primary' => '#3b82f6',
        'danger' => '#ef4444',
        'success' => '#22c55e',
        'warning' => '#f59e0b',
        'info' => '#06b6d4',
        'gray' => '#6b7280',
    ];

    public array $components = [
        'sidebar' => [
            'background' => '',
            'text_color' => '',
            'border_radius' => '0',
        ],
        'header' => [
            'background' => '',
            'text_color' => '',
            'sticky' => true,
        ],
        'cards' => [
            'background' => '',
            'border_radius' => '8',
            'shadow' => 'md',
        ],
        'buttons' => [
            'border_radius' => '6',
            'shadow' => 'sm',
        ],
    ];

    public array $spacing = [
        'content_padding' => '16',
        'card_padding' => '16',
        'sidebar_width' => '280',
    ];

    public string $previewMode = 'desktop';

    public array $history = [];
    public int $historyIndex = -1;

    public string $customCss = '';

    public array $fonts = [
        'heading' => ['family' => 'Inter', 'weight' => 600, 'size' => 'default'],
        'body' => ['family' => 'Inter', 'weight' => 400, 'size' => 'default'],
        'mono' => ['family' => 'JetBrains Mono', 'weight' => 400, 'size' => 'default'],
    ];

    public array $brand = [
        'logo' => null,
        'logo_dark' => null,
        'favicon' => null,
        'login_style' => 'centered',
        'login_background' => null,
        'show_app_name' => true,
    ];

    public function mount(): void
    {
        $themeManager = app(ThemeManager::class);
        
        // Load current theme settings
        $currentColors = $themeManager->getCurrentColors();
        if ($currentColors) {
            $this->colors = array_merge($this->colors, $currentColors);
        }
        
        $this->customCss = $themeManager->getCustomCss() ?? '';
        
        // Save initial state to history
        $this->saveToHistory();
    }

    public function updateColor(string $slot, string $color): void
    {
        $this->colors[$slot] = $color;
        $this->saveToHistory();
        $this->dispatch('theme-preview-updated', colors: $this->colors);
    }

    public function updateComponent(string $component, string $property, mixed $value): void
    {
        $this->components[$component][$property] = $value;
        $this->saveToHistory();
        $this->dispatch('theme-preview-updated', components: $this->components);
    }

    public function updateSpacing(string $property, string $value): void
    {
        $this->spacing[$property] = $value;
        $this->saveToHistory();
        $this->dispatch('theme-preview-updated', spacing: $this->spacing);
    }

    public function updateFont(string $type, string $property, mixed $value): void
    {
        $this->fonts[$type][$property] = $value;
        $this->saveToHistory();
        $this->dispatch('theme-preview-updated', fonts: $this->fonts);
    }

    public function getAvailableFonts(): array
    {
        return FontManager::getSansSerifFonts();
    }

    public function getMonoFonts(): array
    {
        return FontManager::getMonospaceFonts();
    }

    public function getFontWeights(string $fontName): array
    {
        return FontManager::getFontWeights($fontName);
    }

    public function getFontSizes(): array
    {
        return FontManager::getFontSizes();
    }

    public function applyBrandPreset(string $presetName): void
    {
        $preset = BrandManager::getPreset($presetName);
        
        if ($preset) {
            $this->brand['login_style'] = $preset['login_style'] ?? 'centered';
            $this->brand['show_app_name'] = $preset['show_app_name'] ?? true;
            $this->saveToHistory();
            $this->dispatch('brand-preset-applied', preset: $presetName);
        }
    }

    public function setPreviewMode(string $mode): void
    {
        $this->previewMode = $mode;
    }

    public function generatePalette(string $type): void
    {
        $primary = $this->colors['primary'];
        
        $palette = match($type) {
            'complementary' => ColorPaletteGenerator::complementary($primary),
            'analogous' => ColorPaletteGenerator::analogous($primary),
            'triadic' => ColorPaletteGenerator::triadic($primary),
            'split' => ColorPaletteGenerator::splitComplementary($primary),
            default => [],
        };
        
        if (!empty($palette)) {
            if (isset($palette['complementary'])) {
                $this->colors['danger'] = $palette['complementary'];
            }
            if (isset($palette['analogous_left'])) {
                $this->colors['info'] = $palette['analogous_left'];
            }
            if (isset($palette['analogous_right'])) {
                $this->colors['success'] = $palette['analogous_right'];
            }
            if (isset($palette['triadic_1'])) {
                $this->colors['warning'] = $palette['triadic_1'];
            }
            if (isset($palette['triadic_2'])) {
                $this->colors['info'] = $palette['triadic_2'];
            }
        }
        
        $this->saveToHistory();
        $this->dispatch('theme-preview-updated', colors: $this->colors);
    }

    public function saveToHistory(): void
    {
        // Remove any future states if we're not at the end
        if ($this->historyIndex < count($this->history) - 1) {
            $this->history = array_slice($this->history, 0, $this->historyIndex + 1);
        }
        
        $this->history[] = [
            'colors' => $this->colors,
            'components' => $this->components,
            'spacing' => $this->spacing,
        ];
        
        // Limit history to 50 states
        if (count($this->history) > 50) {
            array_shift($this->history);
        } else {
            $this->historyIndex++;
        }
    }

    public function undo(): void
    {
        if ($this->historyIndex > 0) {
            $this->historyIndex--;
            $state = $this->history[$this->historyIndex];
            $this->colors = $state['colors'];
            $this->components = $state['components'];
            $this->spacing = $state['spacing'];
            $this->dispatch('theme-preview-updated', colors: $this->colors, components: $this->components);
        }
    }

    public function redo(): void
    {
        if ($this->historyIndex < count($this->history) - 1) {
            $this->historyIndex++;
            $state = $this->history[$this->historyIndex];
            $this->colors = $state['colors'];
            $this->components = $state['components'];
            $this->spacing = $state['spacing'];
            $this->dispatch('theme-preview-updated', colors: $this->colors, components: $this->components);
        }
    }

    public function canUndo(): bool
    {
        return $this->historyIndex > 0;
    }

    public function canRedo(): bool
    {
        return $this->historyIndex < count($this->history) - 1;
    }

    public function applyTheme(): void
    {
        $themeManager = app(ThemeManager::class);
        
        // Save the theme with colors and custom CSS
        $themeManager->setTheme(
            $themeManager->getCurrentTheme() ?? 'default',
            $this->colors,
            $themeManager->getDarkMode(),
            $this->generateComponentCss()
        );
        
        // Dispatch browser event to trigger page reload
        $this->dispatch('theme-applied-reload');
    }

    public function generateComponentCss(): string
    {
        $css = $this->customCss ? $this->customCss . "\n\n" : '';
        
        // Font styles
        $fontCss = FontManager::generateFontCSS($this->fonts);
        if ($fontCss) {
            $css .= $fontCss . "\n";
        }
        
        // Sidebar styles
        if (!empty($this->components['sidebar']['background'])) {
            $css .= ".fi-sidebar { background: {$this->components['sidebar']['background']}; }\n";
        }
        if (!empty($this->components['sidebar']['border_radius'])) {
            $css .= ".fi-sidebar-nav-item { border-radius: {$this->components['sidebar']['border_radius']}px; }\n";
        }
        
        // Header styles
        if ($this->components['header']['sticky']) {
            $css .= ".fi-topbar { position: sticky; top: 0; z-index: 50; }\n";
        }
        if (!empty($this->components['header']['background'])) {
            $css .= ".fi-topbar { background: {$this->components['header']['background']}; }\n";
        }
        
        // Card styles
        if (!empty($this->components['cards']['border_radius'])) {
            $css .= ".fi-section { border-radius: {$this->components['cards']['border_radius']}px; }\n";
        }
        
        // Button styles
        if (!empty($this->components['buttons']['border_radius'])) {
            $css .= ".fi-btn { border-radius: {$this->components['buttons']['border_radius']}px; }\n";
        }
        
        // Spacing
        if (!empty($this->spacing['sidebar_width'])) {
            $css .= ":root { --sidebar-width: {$this->spacing['sidebar_width']}px; }\n";
        }
        
        return $css;
    }

    public function resetToDefault(): void
    {
        $this->colors = [
            'primary' => '#3b82f6',
            'danger' => '#ef4444',
            'success' => '#22c55e',
            'warning' => '#f59e0b',
            'info' => '#06b6d4',
            'gray' => '#6b7280',
        ];
        
        $this->components = [
            'sidebar' => ['background' => '', 'text_color' => '', 'border_radius' => '0'],
            'header' => ['background' => '', 'text_color' => '', 'sticky' => true],
            'cards' => ['background' => '', 'border_radius' => '8', 'shadow' => 'md'],
            'buttons' => ['border_radius' => '6', 'shadow' => 'sm'],
        ];
        
        $this->spacing = [
            'content_padding' => '16',
            'card_padding' => '16',
            'sidebar_width' => '280',
        ];
        
        $this->saveToHistory();
        $this->dispatch('theme-preview-updated', colors: $this->colors);
    }

    public function render()
    {
        return view('filament-theme-switcher::livewire.theme-builder', [
            'availableFonts' => $this->getAvailableFonts(),
            'monoFonts' => $this->getMonoFonts(),
            'fontSizes' => $this->getFontSizes(),
            'fontWeights' => FontManager::getWeightOptions(),
        ]);
    }
}
