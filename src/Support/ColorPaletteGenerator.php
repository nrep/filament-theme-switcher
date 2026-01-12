<?php

namespace Isura\FilamentThemeSwitcher\Support;

class ColorPaletteGenerator
{
    /**
     * Generate a complementary color palette.
     * Complementary colors are opposite on the color wheel.
     */
    public static function complementary(string $hexColor): array
    {
        $rgb = self::hexToRgb($hexColor);
        $hsl = self::rgbToHsl($rgb['r'], $rgb['g'], $rgb['b']);
        
        // Complementary is 180 degrees opposite
        $complementaryHue = ($hsl['h'] + 180) % 360;
        
        $complementaryRgb = self::hslToRgb($complementaryHue, $hsl['s'], $hsl['l']);
        
        return [
            'primary' => $hexColor,
            'complementary' => self::rgbToHex($complementaryRgb['r'], $complementaryRgb['g'], $complementaryRgb['b']),
        ];
    }

    /**
     * Generate an analogous color palette.
     * Analogous colors are adjacent on the color wheel (30 degrees apart).
     */
    public static function analogous(string $hexColor): array
    {
        $rgb = self::hexToRgb($hexColor);
        $hsl = self::rgbToHsl($rgb['r'], $rgb['g'], $rgb['b']);
        
        $colors = [
            'primary' => $hexColor,
        ];
        
        // Generate colors at -30, +30 degrees
        foreach ([-30, 30] as $index => $offset) {
            $newHue = ($hsl['h'] + $offset + 360) % 360;
            $newRgb = self::hslToRgb($newHue, $hsl['s'], $hsl['l']);
            $key = $offset < 0 ? 'analogous_left' : 'analogous_right';
            $colors[$key] = self::rgbToHex($newRgb['r'], $newRgb['g'], $newRgb['b']);
        }
        
        return $colors;
    }

    /**
     * Generate a triadic color palette.
     * Triadic colors are evenly spaced (120 degrees apart).
     */
    public static function triadic(string $hexColor): array
    {
        $rgb = self::hexToRgb($hexColor);
        $hsl = self::rgbToHsl($rgb['r'], $rgb['g'], $rgb['b']);
        
        $colors = [
            'primary' => $hexColor,
        ];
        
        // Generate colors at 120 and 240 degrees
        foreach ([120, 240] as $index => $offset) {
            $newHue = ($hsl['h'] + $offset) % 360;
            $newRgb = self::hslToRgb($newHue, $hsl['s'], $hsl['l']);
            $colors['triadic_' . ($index + 1)] = self::rgbToHex($newRgb['r'], $newRgb['g'], $newRgb['b']);
        }
        
        return $colors;
    }

    /**
     * Generate a split-complementary color palette.
     * Uses the two colors adjacent to the complement.
     */
    public static function splitComplementary(string $hexColor): array
    {
        $rgb = self::hexToRgb($hexColor);
        $hsl = self::rgbToHsl($rgb['r'], $rgb['g'], $rgb['b']);
        
        $colors = [
            'primary' => $hexColor,
        ];
        
        // Split complementary: 150 and 210 degrees from primary
        foreach ([150, 210] as $index => $offset) {
            $newHue = ($hsl['h'] + $offset) % 360;
            $newRgb = self::hslToRgb($newHue, $hsl['s'], $hsl['l']);
            $colors['split_' . ($index + 1)] = self::rgbToHex($newRgb['r'], $newRgb['g'], $newRgb['b']);
        }
        
        return $colors;
    }

    /**
     * Generate shades of a color (darker variations).
     */
    public static function shades(string $hexColor, int $count = 5): array
    {
        $rgb = self::hexToRgb($hexColor);
        $hsl = self::rgbToHsl($rgb['r'], $rgb['g'], $rgb['b']);
        
        $shades = [];
        $step = $hsl['l'] / ($count + 1);
        
        for ($i = 1; $i <= $count; $i++) {
            $newL = max(0, $hsl['l'] - ($step * $i));
            $newRgb = self::hslToRgb($hsl['h'], $hsl['s'], $newL);
            $shades["shade_{$i}"] = self::rgbToHex($newRgb['r'], $newRgb['g'], $newRgb['b']);
        }
        
        return $shades;
    }

    /**
     * Generate tints of a color (lighter variations).
     */
    public static function tints(string $hexColor, int $count = 5): array
    {
        $rgb = self::hexToRgb($hexColor);
        $hsl = self::rgbToHsl($rgb['r'], $rgb['g'], $rgb['b']);
        
        $tints = [];
        $step = (100 - $hsl['l']) / ($count + 1);
        
        for ($i = 1; $i <= $count; $i++) {
            $newL = min(100, $hsl['l'] + ($step * $i));
            $newRgb = self::hslToRgb($hsl['h'], $hsl['s'], $newL);
            $tints["tint_{$i}"] = self::rgbToHex($newRgb['r'], $newRgb['g'], $newRgb['b']);
        }
        
        return $tints;
    }

    /**
     * Generate a complete Filament-compatible color palette.
     */
    public static function generateFilamentPalette(string $hexColor): array
    {
        $rgb = self::hexToRgb($hexColor);
        $hsl = self::rgbToHsl($rgb['r'], $rgb['g'], $rgb['b']);
        
        // Generate 50-950 scale like Tailwind
        $palette = [];
        $lightnesses = [
            50 => 95,
            100 => 90,
            200 => 80,
            300 => 70,
            400 => 60,
            500 => 50,
            600 => 40,
            700 => 30,
            800 => 20,
            900 => 15,
            950 => 10,
        ];
        
        foreach ($lightnesses as $key => $l) {
            $newRgb = self::hslToRgb($hsl['h'], $hsl['s'], $l);
            $palette[$key] = "{$newRgb['r']}, {$newRgb['g']}, {$newRgb['b']}";
        }
        
        return $palette;
    }

    /**
     * Convert hex color to RGB.
     */
    public static function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        
        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2)),
        ];
    }

    /**
     * Convert RGB to hex color.
     */
    public static function rgbToHex(int $r, int $g, int $b): string
    {
        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    /**
     * Convert RGB to HSL.
     */
    public static function rgbToHsl(int $r, int $g, int $b): array
    {
        $r /= 255;
        $g /= 255;
        $b /= 255;
        
        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $l = ($max + $min) / 2;
        
        if ($max === $min) {
            $h = $s = 0;
        } else {
            $d = $max - $min;
            $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
            
            switch ($max) {
                case $r:
                    $h = (($g - $b) / $d + ($g < $b ? 6 : 0)) / 6;
                    break;
                case $g:
                    $h = (($b - $r) / $d + 2) / 6;
                    break;
                case $b:
                    $h = (($r - $g) / $d + 4) / 6;
                    break;
            }
        }
        
        return [
            'h' => round($h * 360),
            's' => round($s * 100),
            'l' => round($l * 100),
        ];
    }

    /**
     * Convert HSL to RGB.
     */
    public static function hslToRgb(float $h, float $s, float $l): array
    {
        $h /= 360;
        $s /= 100;
        $l /= 100;
        
        if ($s === 0.0) {
            $r = $g = $b = $l;
        } else {
            $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
            $p = 2 * $l - $q;
            
            $r = self::hueToRgb($p, $q, $h + 1/3);
            $g = self::hueToRgb($p, $q, $h);
            $b = self::hueToRgb($p, $q, $h - 1/3);
        }
        
        return [
            'r' => round($r * 255),
            'g' => round($g * 255),
            'b' => round($b * 255),
        ];
    }

    /**
     * Helper function for HSL to RGB conversion.
     */
    private static function hueToRgb(float $p, float $q, float $t): float
    {
        if ($t < 0) $t += 1;
        if ($t > 1) $t -= 1;
        if ($t < 1/6) return $p + ($q - $p) * 6 * $t;
        if ($t < 1/2) return $q;
        if ($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;
        return $p;
    }
}
