<?php

namespace Isura\FilamentThemeSwitcher\Support;

class FontManager
{
    protected static array $popularFonts = [
        // Sans-serif
        'Inter' => ['weights' => [300, 400, 500, 600, 700], 'category' => 'sans-serif'],
        'Roboto' => ['weights' => [300, 400, 500, 700], 'category' => 'sans-serif'],
        'Open Sans' => ['weights' => [300, 400, 500, 600, 700], 'category' => 'sans-serif'],
        'Lato' => ['weights' => [300, 400, 700], 'category' => 'sans-serif'],
        'Poppins' => ['weights' => [300, 400, 500, 600, 700], 'category' => 'sans-serif'],
        'Nunito' => ['weights' => [300, 400, 500, 600, 700], 'category' => 'sans-serif'],
        'Montserrat' => ['weights' => [300, 400, 500, 600, 700], 'category' => 'sans-serif'],
        'Raleway' => ['weights' => [300, 400, 500, 600, 700], 'category' => 'sans-serif'],
        'Ubuntu' => ['weights' => [300, 400, 500, 700], 'category' => 'sans-serif'],
        'Nunito Sans' => ['weights' => [300, 400, 600, 700], 'category' => 'sans-serif'],
        'Work Sans' => ['weights' => [300, 400, 500, 600, 700], 'category' => 'sans-serif'],
        'DM Sans' => ['weights' => [400, 500, 700], 'category' => 'sans-serif'],
        'Plus Jakarta Sans' => ['weights' => [300, 400, 500, 600, 700], 'category' => 'sans-serif'],
        
        // Serif
        'Playfair Display' => ['weights' => [400, 500, 600, 700], 'category' => 'serif'],
        'Merriweather' => ['weights' => [300, 400, 700], 'category' => 'serif'],
        'Lora' => ['weights' => [400, 500, 600, 700], 'category' => 'serif'],
        'PT Serif' => ['weights' => [400, 700], 'category' => 'serif'],
        'Crimson Text' => ['weights' => [400, 600, 700], 'category' => 'serif'],
        'Source Serif Pro' => ['weights' => [400, 600, 700], 'category' => 'serif'],
        
        // Monospace
        'JetBrains Mono' => ['weights' => [400, 500, 600, 700], 'category' => 'monospace'],
        'Fira Code' => ['weights' => [300, 400, 500, 600, 700], 'category' => 'monospace'],
        'Source Code Pro' => ['weights' => [400, 500, 600, 700], 'category' => 'monospace'],
        'IBM Plex Mono' => ['weights' => [400, 500, 600, 700], 'category' => 'monospace'],
        'Roboto Mono' => ['weights' => [400, 500, 600, 700], 'category' => 'monospace'],
        'Ubuntu Mono' => ['weights' => [400, 700], 'category' => 'monospace'],
    ];

    protected static array $systemFonts = [
        'System UI' => ['value' => 'ui-sans-serif, system-ui, sans-serif', 'category' => 'system'],
        'System Serif' => ['value' => 'ui-serif, Georgia, serif', 'category' => 'system'],
        'System Mono' => ['value' => 'ui-monospace, monospace', 'category' => 'system'],
    ];

    public static function getPopularFonts(): array
    {
        return self::$popularFonts;
    }

    public static function getSystemFonts(): array
    {
        return self::$systemFonts;
    }

    public static function getAllFonts(): array
    {
        return array_merge(self::$systemFonts, self::$popularFonts);
    }

    public static function getFontsByCategory(string $category): array
    {
        return array_filter(self::$popularFonts, fn($font) => $font['category'] === $category);
    }

    public static function getSansSerifFonts(): array
    {
        return array_merge(
            ['System UI' => self::$systemFonts['System UI']],
            self::getFontsByCategory('sans-serif')
        );
    }

    public static function getSerifFonts(): array
    {
        return array_merge(
            ['System Serif' => self::$systemFonts['System Serif']],
            self::getFontsByCategory('serif')
        );
    }

    public static function getMonospaceFonts(): array
    {
        return array_merge(
            ['System Mono' => self::$systemFonts['System Mono']],
            self::getFontsByCategory('monospace')
        );
    }

    public static function getFontWeights(string $fontName): array
    {
        if (isset(self::$popularFonts[$fontName])) {
            return self::$popularFonts[$fontName]['weights'];
        }
        
        return [400, 500, 600, 700]; // Default weights
    }

    public static function generateGoogleFontsUrl(array $fonts): string
    {
        if (empty($fonts)) {
            return '';
        }

        $families = [];
        
        foreach ($fonts as $fontName => $weights) {
            if (isset(self::$systemFonts[$fontName])) {
                continue; // Skip system fonts
            }
            
            $weightsStr = is_array($weights) ? implode(';', $weights) : $weights;
            $encodedName = urlencode($fontName);
            $families[] = "family={$encodedName}:wght@{$weightsStr}";
        }

        if (empty($families)) {
            return '';
        }

        return 'https://fonts.googleapis.com/css2?' . implode('&', $families) . '&display=swap';
    }

    public static function generateFontFaceCSS(string $fontName, array $weights = [400, 500, 600, 700]): string
    {
        if (isset(self::$systemFonts[$fontName])) {
            return ''; // System fonts don't need @font-face
        }

        $url = self::generateGoogleFontsUrl([$fontName => implode(';', $weights)]);
        
        if (empty($url)) {
            return '';
        }

        return "@import url('{$url}');";
    }

    public static function getFontStack(string $fontName): string
    {
        if (isset(self::$systemFonts[$fontName])) {
            return self::$systemFonts[$fontName]['value'];
        }

        $category = self::$popularFonts[$fontName]['category'] ?? 'sans-serif';
        
        $fallback = match($category) {
            'serif' => 'Georgia, serif',
            'monospace' => 'monospace',
            default => 'ui-sans-serif, system-ui, sans-serif',
        };

        return "'{$fontName}', {$fallback}";
    }

    public static function getDefaultFontSettings(): array
    {
        return [
            'heading' => [
                'family' => 'Inter',
                'weight' => 600,
                'size' => 'default',
            ],
            'body' => [
                'family' => 'Inter',
                'weight' => 400,
                'size' => 'default',
            ],
            'mono' => [
                'family' => 'JetBrains Mono',
                'weight' => 400,
                'size' => 'default',
            ],
        ];
    }

    public static function getFontSizes(): array
    {
        return [
            'xs' => ['label' => 'Extra Small', 'scale' => 0.75],
            'sm' => ['label' => 'Small', 'scale' => 0.875],
            'default' => ['label' => 'Default', 'scale' => 1],
            'lg' => ['label' => 'Large', 'scale' => 1.125],
            'xl' => ['label' => 'Extra Large', 'scale' => 1.25],
        ];
    }

    public static function generateFontCSS(array $settings): string
    {
        $css = '';
        
        // Import Google Fonts
        $fontsToLoad = [];
        foreach (['heading', 'body', 'mono'] as $type) {
            if (isset($settings[$type]['family'])) {
                $family = $settings[$type]['family'];
                $weight = $settings[$type]['weight'] ?? 400;
                
                if (!isset(self::$systemFonts[$family])) {
                    if (!isset($fontsToLoad[$family])) {
                        $fontsToLoad[$family] = [];
                    }
                    $fontsToLoad[$family][] = $weight;
                }
            }
        }

        if (!empty($fontsToLoad)) {
            $families = [];
            foreach ($fontsToLoad as $family => $weights) {
                $uniqueWeights = array_unique($weights);
                sort($uniqueWeights);
                $families[$family] = implode(';', $uniqueWeights);
            }
            $url = self::generateGoogleFontsUrl($families);
            if ($url) {
                $css .= "@import url('{$url}');\n\n";
            }
        }

        // Generate CSS variables
        $css .= ":root {\n";
        
        if (isset($settings['heading']['family'])) {
            $css .= "  --font-heading: " . self::getFontStack($settings['heading']['family']) . ";\n";
        }
        if (isset($settings['body']['family'])) {
            $css .= "  --font-body: " . self::getFontStack($settings['body']['family']) . ";\n";
        }
        if (isset($settings['mono']['family'])) {
            $css .= "  --font-mono: " . self::getFontStack($settings['mono']['family']) . ";\n";
        }

        // Font sizes
        $sizes = self::getFontSizes();
        if (isset($settings['body']['size']) && isset($sizes[$settings['body']['size']])) {
            $scale = $sizes[$settings['body']['size']]['scale'];
            $css .= "  --font-size-scale: {$scale};\n";
        }

        $css .= "}\n\n";

        // Apply font families
        $css .= "body { font-family: var(--font-body); }\n";
        $css .= "h1, h2, h3, h4, h5, h6, .fi-header-heading { font-family: var(--font-heading); }\n";
        $css .= "code, pre, .fi-code { font-family: var(--font-mono); }\n";

        return $css;
    }
}
