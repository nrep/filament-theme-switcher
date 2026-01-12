<?php

namespace Isura\FilamentThemeSwitcher\Support;

class CssSnippets
{
    /**
     * Get all available CSS snippets organized by category.
     */
    public static function all(): array
    {
        return [
            'sidebar' => self::sidebarSnippets(),
            'header' => self::headerSnippets(),
            'cards' => self::cardSnippets(),
            'buttons' => self::buttonSnippets(),
            'tables' => self::tableSnippets(),
            'forms' => self::formSnippets(),
            'utilities' => self::utilitySnippets(),
        ];
    }

    /**
     * Get a flat list of all snippets.
     */
    public static function flat(): array
    {
        $snippets = [];
        foreach (self::all() as $category => $categorySnippets) {
            foreach ($categorySnippets as $snippet) {
                $snippet['category'] = $category;
                $snippets[] = $snippet;
            }
        }
        return $snippets;
    }

    /**
     * Sidebar customization snippets.
     */
    public static function sidebarSnippets(): array
    {
        return [
            [
                'name' => 'Dark Sidebar',
                'description' => 'Apply a dark background to the sidebar',
                'css' => ".fi-sidebar {\n    background: #1a1a2e;\n}",
            ],
            [
                'name' => 'Gradient Sidebar',
                'description' => 'Apply a gradient background to the sidebar',
                'css' => ".fi-sidebar {\n    background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);\n}",
            ],
            [
                'name' => 'Rounded Sidebar Items',
                'description' => 'Make sidebar navigation items more rounded',
                'css' => ".fi-sidebar-nav-item {\n    border-radius: 0.75rem;\n}",
            ],
            [
                'name' => 'Sidebar Icon Glow',
                'description' => 'Add a glow effect to active sidebar icons',
                'css' => ".fi-sidebar-item-active .fi-sidebar-item-icon {\n    filter: drop-shadow(0 0 8px currentColor);\n}",
            ],
        ];
    }

    /**
     * Header customization snippets.
     */
    public static function headerSnippets(): array
    {
        return [
            [
                'name' => 'Sticky Header',
                'description' => 'Make the header stick to the top when scrolling',
                'css' => ".fi-topbar {\n    position: sticky;\n    top: 0;\n    z-index: 50;\n}",
            ],
            [
                'name' => 'Glass Header',
                'description' => 'Apply a glassmorphism effect to the header',
                'css' => ".fi-topbar {\n    background: rgba(255, 255, 255, 0.8);\n    backdrop-filter: blur(10px);\n}\n.dark .fi-topbar {\n    background: rgba(0, 0, 0, 0.8);\n}",
            ],
            [
                'name' => 'Header Shadow',
                'description' => 'Add a subtle shadow to the header',
                'css' => ".fi-topbar {\n    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);\n}",
            ],
        ];
    }

    /**
     * Card customization snippets.
     */
    public static function cardSnippets(): array
    {
        return [
            [
                'name' => 'Card Hover Effect',
                'description' => 'Add a lift effect on card hover',
                'css' => ".fi-section {\n    transition: transform 0.2s, box-shadow 0.2s;\n}\n.fi-section:hover {\n    transform: translateY(-2px);\n    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);\n}",
            ],
            [
                'name' => 'Rounded Cards',
                'description' => 'Make cards more rounded',
                'css' => ".fi-section {\n    border-radius: 1rem;\n}",
            ],
            [
                'name' => 'Card Border Accent',
                'description' => 'Add a colored left border to cards',
                'css' => ".fi-section {\n    border-left: 4px solid rgb(var(--primary-500));\n}",
            ],
        ];
    }

    /**
     * Button customization snippets.
     */
    public static function buttonSnippets(): array
    {
        return [
            [
                'name' => 'Pill Buttons',
                'description' => 'Make buttons fully rounded (pill shape)',
                'css' => ".fi-btn {\n    border-radius: 9999px;\n}",
            ],
            [
                'name' => 'Button Shadows',
                'description' => 'Add shadows to primary buttons',
                'css' => ".fi-btn-primary {\n    box-shadow: 0 4px 14px 0 rgba(var(--primary-500), 0.39);\n}",
            ],
            [
                'name' => 'Button Hover Scale',
                'description' => 'Scale up buttons on hover',
                'css' => ".fi-btn {\n    transition: transform 0.15s;\n}\n.fi-btn:hover {\n    transform: scale(1.02);\n}",
            ],
        ];
    }

    /**
     * Table customization snippets.
     */
    public static function tableSnippets(): array
    {
        return [
            [
                'name' => 'Striped Table Rows',
                'description' => 'Add alternating background colors to table rows',
                'css' => ".fi-ta-row:nth-child(even) {\n    background-color: rgba(var(--gray-100), 0.5);\n}\n.dark .fi-ta-row:nth-child(even) {\n    background-color: rgba(var(--gray-800), 0.3);\n}",
            ],
            [
                'name' => 'Table Row Hover',
                'description' => 'Highlight table rows on hover',
                'css' => ".fi-ta-row:hover {\n    background-color: rgba(var(--primary-500), 0.05);\n}",
            ],
            [
                'name' => 'Rounded Table',
                'description' => 'Add rounded corners to tables',
                'css' => ".fi-ta {\n    border-radius: 0.75rem;\n    overflow: hidden;\n}",
            ],
        ];
    }

    /**
     * Form customization snippets.
     */
    public static function formSnippets(): array
    {
        return [
            [
                'name' => 'Floating Labels',
                'description' => 'Style for floating label effect',
                'css' => ".fi-fo-field-wrp label {\n    font-size: 0.75rem;\n    text-transform: uppercase;\n    letter-spacing: 0.05em;\n    color: rgb(var(--gray-500));\n}",
            ],
            [
                'name' => 'Input Focus Glow',
                'description' => 'Add a glow effect to focused inputs',
                'css' => ".fi-input:focus {\n    box-shadow: 0 0 0 3px rgba(var(--primary-500), 0.2);\n}",
            ],
            [
                'name' => 'Rounded Inputs',
                'description' => 'Make form inputs more rounded',
                'css' => ".fi-input, .fi-select {\n    border-radius: 0.75rem;\n}",
            ],
        ];
    }

    /**
     * Utility snippets.
     */
    public static function utilitySnippets(): array
    {
        return [
            [
                'name' => 'Smooth Scrolling',
                'description' => 'Enable smooth scrolling throughout the panel',
                'css' => "html {\n    scroll-behavior: smooth;\n}",
            ],
            [
                'name' => 'Custom Scrollbar',
                'description' => 'Style the scrollbar for webkit browsers',
                'css' => "::-webkit-scrollbar {\n    width: 8px;\n}\n::-webkit-scrollbar-track {\n    background: rgb(var(--gray-100));\n}\n::-webkit-scrollbar-thumb {\n    background: rgb(var(--gray-400));\n    border-radius: 4px;\n}\n::-webkit-scrollbar-thumb:hover {\n    background: rgb(var(--gray-500));\n}",
            ],
            [
                'name' => 'Hide Scrollbar',
                'description' => 'Hide scrollbars while keeping scroll functionality',
                'css' => ".fi-main {\n    scrollbar-width: none;\n    -ms-overflow-style: none;\n}\n.fi-main::-webkit-scrollbar {\n    display: none;\n}",
            ],
            [
                'name' => 'Animations Disabled',
                'description' => 'Disable all animations for accessibility',
                'css' => "@media (prefers-reduced-motion: reduce) {\n    * {\n        animation: none !important;\n        transition: none !important;\n    }\n}",
            ],
        ];
    }

    /**
     * Validate CSS syntax (basic validation).
     */
    public static function validate(string $css): array
    {
        $errors = [];
        
        // Check for balanced braces
        $openBraces = substr_count($css, '{');
        $closeBraces = substr_count($css, '}');
        
        if ($openBraces !== $closeBraces) {
            $errors[] = 'Unbalanced curly braces: ' . $openBraces . ' opening, ' . $closeBraces . ' closing';
        }
        
        // Check for potentially dangerous content
        $dangerous = ['javascript:', 'expression(', 'url(data:text/html', '<script', '</script'];
        foreach ($dangerous as $pattern) {
            if (stripos($css, $pattern) !== false) {
                $errors[] = 'Potentially dangerous content detected: ' . $pattern;
            }
        }
        
        // Check for @import (usually not allowed in inline styles)
        if (preg_match('/@import\s/i', $css)) {
            $errors[] = '@import statements are not allowed';
        }
        
        // Check for empty rules
        if (preg_match('/\{\s*\}/', $css)) {
            $errors[] = 'Empty CSS rules detected';
        }
        
        return $errors;
    }

    /**
     * Scope CSS to prevent conflicts with external styles.
     */
    public static function scope(string $css, string $prefix = '.fi-theme-custom'): string
    {
        // Simple scoping - prepend prefix to each selector
        $scoped = preg_replace_callback(
            '/([^{}]+)(\{[^{}]*\})/',
            function ($matches) use ($prefix) {
                $selectors = $matches[1];
                $rules = $matches[2];
                
                // Split multiple selectors and scope each
                $selectorList = array_map('trim', explode(',', $selectors));
                $scopedSelectors = array_map(function ($selector) use ($prefix) {
                    // Don't scope @-rules
                    if (strpos($selector, '@') === 0) {
                        return $selector;
                    }
                    // Don't double-scope
                    if (strpos($selector, $prefix) === 0) {
                        return $selector;
                    }
                    return $prefix . ' ' . $selector;
                }, $selectorList);
                
                return implode(', ', $scopedSelectors) . $rules;
            },
            $css
        );
        
        return $scoped;
    }
}
