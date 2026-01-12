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
        // Load all theme builder state from session
        $savedState = session()->get('filament_theme_builder_state');
        
        if ($savedState && is_array($savedState)) {
            if (isset($savedState['colors']) && is_array($savedState['colors'])) {
                $this->colors = array_merge($this->colors, $savedState['colors']);
            }
            if (isset($savedState['fonts']) && is_array($savedState['fonts'])) {
                $this->fonts = array_merge($this->fonts, $savedState['fonts']);
            }
            if (isset($savedState['components']) && is_array($savedState['components'])) {
                $this->components = array_merge($this->components, $savedState['components']);
            }
            if (isset($savedState['spacing']) && is_array($savedState['spacing'])) {
                $this->spacing = array_merge($this->spacing, $savedState['spacing']);
            }
            if (isset($savedState['brand']) && is_array($savedState['brand'])) {
                $this->brand = array_merge($this->brand, $savedState['brand']);
            }
            if (isset($savedState['customCss'])) {
                $this->customCss = $savedState['customCss'];
            }
        } else {
            // Fallback to ThemeManager for colors only
            $themeManager = app(ThemeManager::class);
            $currentColors = $themeManager->getCurrentColors();
            if ($currentColors && is_array($currentColors)) {
                $this->colors = array_merge($this->colors, $currentColors);
            }
        }
        
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
            $this->brand = array_merge($this->brand, [
                'login_style' => $preset['login_style'] ?? 'centered',
                'show_app_name' => $preset['show_app_name'] ?? true,
            ]);
            $this->saveToHistory();
        }
    }

    public function setLoginStyle(string $style): void
    {
        $this->brand['login_style'] = $style;
        $this->saveToHistory();
    }

    public function toggleShowAppName(): void
    {
        $this->brand['show_app_name'] = !$this->brand['show_app_name'];
        $this->saveToHistory();
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
        $generatedCss = $this->generateComponentCss();
        
        // Save all theme builder state to session
        $state = [
            'colors' => $this->colors,
            'fonts' => $this->fonts,
            'components' => $this->components,
            'spacing' => $this->spacing,
            'brand' => $this->brand,
            'customCss' => $generatedCss,
        ];
        session()->put('filament_theme_builder_state', $state);
        
        // Also save through ThemeManager for color application
        $themeManager->setTheme(
            $themeManager->getCurrentTheme() ?? 'default',
            $this->colors,
            $themeManager->getDarkMode(),
            $generatedCss
        );
        
        // Store branding CSS in a file (persists after logout for login page styles)
        $brandingCssPath = storage_path('app/filament-theme-branding.css');
        file_put_contents($brandingCssPath, $generatedCss);
        
        // Force session save before reload
        session()->save();
        
        // Show success notification
        \Filament\Notifications\Notification::make()
            ->title('Theme Applied')
            ->body('Your theme settings have been saved successfully.')
            ->success()
            ->send();
        
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
        if (!empty($this->spacing['content_padding'])) {
            $css .= ".fi-main { padding: {$this->spacing['content_padding']}px; }\n";
        }
        
        // Branding - Login page styles
        $loginStyle = $this->brand['login_style'] ?? 'centered';
        $primaryColor = $this->colors['primary'] ?? '#3b82f6';
        
        if ($loginStyle === 'gradient') {
            // Rich gradient background with glassmorphism card
            $css .= ".fi-simple-layout { 
                background: linear-gradient(135deg, {$primaryColor} 0%, color-mix(in srgb, {$primaryColor} 50%, #312e81) 50%, #1e1b4b 100%) !important;
                min-height: 100vh;
            }\n";
            $css .= ".fi-simple-main { 
                backdrop-filter: blur(16px) !important;
                background: rgba(255, 255, 255, 0.95) !important;
                border-radius: 16px !important;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4) !important;
            }\n";
            $css .= ".dark .fi-simple-main {
                background: rgba(15, 23, 42, 0.9) !important;
                border: 1px solid rgba(255, 255, 255, 0.1) !important;
            }\n";
        } elseif ($loginStyle === 'split') {
            // TALL-stack style split layout - gradient left, form right
            $css .= ".fi-simple-layout { 
                display: flex !important;
                min-height: 100vh !important;
                background: #f8fafc !important;
            }\n";
            $css .= ".fi-simple-layout > .fi-simple-main-ctn {
                flex: 1;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem;
                order: 2;
            }\n";
            $css .= ".fi-simple-layout::before { 
                content: '';
                flex: 1;
                order: 1;
                background: linear-gradient(135deg, {$primaryColor} 0%, color-mix(in srgb, {$primaryColor} 40%, #1e1b4b) 100%);
                display: block !important;
            }\n";
            $css .= ".fi-simple-main {
                width: 100%;
                max-width: 400px;
            }\n";
            $css .= ".dark .fi-simple-layout { background: #0f172a !important; }\n";
            $css .= "@media (max-width: 1024px) { 
                .fi-simple-layout::before { display: none !important; }
                .fi-simple-layout { justify-content: center; }
            }\n";
        } elseif ($loginStyle === 'fullscreen') {
            // Subtle background tint with clean card
            $css .= ".fi-simple-layout { 
                background: linear-gradient(to bottom right, color-mix(in srgb, {$primaryColor} 8%, #f8fafc), #f8fafc) !important;
                min-height: 100vh;
            }\n";
            $css .= ".fi-simple-main { 
                background: white !important;
                border-radius: 12px !important;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(0, 0, 0, 0.05) !important;
            }\n";
            $css .= ".dark .fi-simple-layout { background: linear-gradient(to bottom right, color-mix(in srgb, {$primaryColor} 15%, #0f172a), #0f172a) !important; }\n";
            $css .= ".dark .fi-simple-main { background: #1e293b !important; border: 1px solid rgba(255,255,255,0.1) !important; }\n";
        }
        
        // Show/hide app name in sidebar
        if (isset($this->brand['show_app_name']) && !$this->brand['show_app_name']) {
            $css .= ".fi-sidebar-header-heading { display: none; }\n";
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
