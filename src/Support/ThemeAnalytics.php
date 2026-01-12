<?php

namespace Isura\FilamentThemeSwitcher\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ThemeAnalytics
{
    protected static string $cachePrefix = 'theme_analytics_';

    public static function trackThemeUsage(string $themeId, ?string $userId = null): void
    {
        $key = self::$cachePrefix . 'usage_' . date('Y-m-d');
        
        $data = Cache::get($key, []);
        
        if (!isset($data[$themeId])) {
            $data[$themeId] = ['views' => 0, 'users' => []];
        }
        
        $data[$themeId]['views']++;
        
        if ($userId && !in_array($userId, $data[$themeId]['users'])) {
            $data[$themeId]['users'][] = $userId;
        }
        
        Cache::put($key, $data, 86400);
    }

    public static function getThemeUsageStats(string $themeId, int $days = 30): array
    {
        $stats = [
            'total_views' => 0,
            'unique_users' => 0,
            'daily' => [],
        ];

        for ($i = 0; $i < $days; $i++) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $key = self::$cachePrefix . 'usage_' . $date;
            $data = Cache::get($key, []);
            
            if (isset($data[$themeId])) {
                $stats['total_views'] += $data[$themeId]['views'];
                $stats['unique_users'] += count($data[$themeId]['users']);
                $stats['daily'][$date] = $data[$themeId]['views'];
            } else {
                $stats['daily'][$date] = 0;
            }
        }

        return $stats;
    }

    public static function getPopularThemes(int $limit = 10, int $days = 30): array
    {
        $themes = [];

        for ($i = 0; $i < $days; $i++) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $key = self::$cachePrefix . 'usage_' . $date;
            $data = Cache::get($key, []);
            
            foreach ($data as $themeId => $stats) {
                if (!isset($themes[$themeId])) {
                    $themes[$themeId] = ['views' => 0, 'users' => 0];
                }
                $themes[$themeId]['views'] += $stats['views'];
                $themes[$themeId]['users'] += count($stats['users']);
            }
        }

        // Sort by views
        uasort($themes, fn($a, $b) => $b['views'] <=> $a['views']);

        return array_slice($themes, 0, $limit, true);
    }

    public static function getDashboardWidgetData(): array
    {
        $popularThemes = self::getPopularThemes(5, 7);
        $totalViews = array_sum(array_column($popularThemes, 'views'));
        
        return [
            'total_views_week' => $totalViews,
            'popular_themes' => $popularThemes,
            'chart_data' => self::getChartData(7),
        ];
    }

    public static function getChartData(int $days = 7): array
    {
        $labels = [];
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $labels[] = date('M d', strtotime($date));
            
            $key = self::$cachePrefix . 'usage_' . $date;
            $dayData = Cache::get($key, []);
            
            $dayTotal = 0;
            foreach ($dayData as $stats) {
                $dayTotal += $stats['views'];
            }
            $data[] = $dayTotal;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Theme Views',
                    'data' => $data,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
            ],
        ];
    }

    public static function setupABTest(string $testName, array $variants): string
    {
        $testId = 'ab_test_' . md5($testName . time());
        
        Cache::put($testId, [
            'name' => $testName,
            'variants' => $variants,
            'results' => array_fill_keys(array_keys($variants), ['impressions' => 0, 'conversions' => 0]),
            'created_at' => now()->toIso8601String(),
            'status' => 'running',
        ], 86400 * 30); // 30 days

        return $testId;
    }

    public static function getABTestVariant(string $testId, ?string $userId = null): ?string
    {
        $test = Cache::get($testId);
        
        if (!$test || $test['status'] !== 'running') {
            return null;
        }

        $variants = array_keys($test['variants']);
        
        // Consistent assignment based on user ID or random
        if ($userId) {
            $index = crc32($userId . $testId) % count($variants);
        } else {
            $index = array_rand($variants);
        }

        $variant = $variants[$index];
        
        // Track impression
        $test['results'][$variant]['impressions']++;
        Cache::put($testId, $test, 86400 * 30);

        return $test['variants'][$variant];
    }

    public static function trackABTestConversion(string $testId, string $variant): void
    {
        $test = Cache::get($testId);
        
        if (!$test || !isset($test['results'][$variant])) {
            return;
        }

        $test['results'][$variant]['conversions']++;
        Cache::put($testId, $test, 86400 * 30);
    }

    public static function getABTestResults(string $testId): ?array
    {
        $test = Cache::get($testId);
        
        if (!$test) {
            return null;
        }

        $results = [];
        foreach ($test['results'] as $variant => $data) {
            $conversionRate = $data['impressions'] > 0 
                ? round(($data['conversions'] / $data['impressions']) * 100, 2) 
                : 0;
            
            $results[$variant] = [
                'theme' => $test['variants'][$variant],
                'impressions' => $data['impressions'],
                'conversions' => $data['conversions'],
                'conversion_rate' => $conversionRate,
            ];
        }

        return [
            'name' => $test['name'],
            'status' => $test['status'],
            'created_at' => $test['created_at'],
            'results' => $results,
        ];
    }

    public static function endABTest(string $testId): bool
    {
        $test = Cache::get($testId);
        
        if (!$test) {
            return false;
        }

        $test['status'] = 'completed';
        $test['ended_at'] = now()->toIso8601String();
        Cache::put($testId, $test, 86400 * 30);

        return true;
    }

    public static function getActiveABTests(): array
    {
        // In production, this would query the database
        // For now, return empty array as tests are stored in cache
        return [];
    }

    public static function clearAnalyticsCache(): void
    {
        // Clear analytics cache for last 30 days
        for ($i = 0; $i < 30; $i++) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            Cache::forget(self::$cachePrefix . 'usage_' . $date);
        }
    }
}
