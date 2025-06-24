<?php

namespace App\Services\Theme;

use App\Models\LandingPageSetting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ThemeConfigurationService
{
    private array $defaultConfig = [
        // Colors
        'primary_color' => '#3b82f6',
        'secondary_color' => '#64748b',
        'accent_color' => '#f59e0b',
        'success_color' => '#22c55e',
        'warning_color' => '#f59e0b',
        'danger_color' => '#ef4444',
        'info_color' => '#06b6d4',
        
        // Layout
        'sidebar_width' => '280px',
        'header_height' => '80px',
        'border_radius' => '12px',
        'shadow_intensity' => 'medium',
        
        // Typography
        'font_family' => 'Inter',
        'font_size_base' => '14px',
        'font_weight_normal' => '400',
        'font_weight_medium' => '500',
        'font_weight_bold' => '700',
        
        // Branding
        'brand_name' => 'Villa Windaro Permai',
        'brand_logo_url' => '/images/logo.png',
        'brand_favicon_url' => '/images/favicon.ico',
        
        // Backgrounds
        'body_background' => 'linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%)',
        'sidebar_background' => 'linear-gradient(180deg, #1e293b 0%, #334155 100%)',
        'card_background' => '#ffffff',
        
        // Effects
        'enable_animations' => true,
        'enable_dark_mode' => false,
        'enable_glass_effect' => true,
    ];

    public function getConfig(): Collection
    {
        return Cache::remember('theme_config', 3600, function () {
            $settings = LandingPageSetting::whereIn('key', array_keys($this->defaultConfig))
                ->pluck('value', 'key')
                ->toArray();
            
            return collect(array_merge($this->defaultConfig, $settings));
        });
    }

    public function updateConfig(array $config): bool
    {
        try {
            foreach ($config as $key => $value) {
                if (array_key_exists($key, $this->defaultConfig)) {
                    LandingPageSetting::updateOrCreate(
                        ['key' => $key],
                        ['value' => $value]
                    );
                }
            }
            
            Cache::forget('theme_config');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function generateCssVariables(): string
    {
        $config = $this->getConfig();
        
        $cssVars = [];
        
        // Convert colors to CSS variables
        foreach (['primary', 'secondary', 'accent', 'success', 'warning', 'danger', 'info'] as $color) {
            $colorValue = $config->get($color . '_color');
            $cssVars["--color-{$color}"] = $colorValue;
            
            // Generate color shades
            $shades = $this->generateColorShades($colorValue);
            foreach ($shades as $shade => $value) {
                $cssVars["--color-{$color}-{$shade}"] = $value;
            }
        }
        
        // Layout variables
        $cssVars['--sidebar-width'] = $config->get('sidebar_width');
        $cssVars['--header-height'] = $config->get('header_height');
        $cssVars['--border-radius'] = $config->get('border_radius');
        
        // Typography variables
        $cssVars['--font-family'] = $config->get('font_family');
        $cssVars['--font-size-base'] = $config->get('font_size_base');
        $cssVars['--font-weight-normal'] = $config->get('font_weight_normal');
        $cssVars['--font-weight-medium'] = $config->get('font_weight_medium');
        $cssVars['--font-weight-bold'] = $config->get('font_weight_bold');
        
        // Background variables
        $cssVars['--body-background'] = $config->get('body_background');
        $cssVars['--sidebar-background'] = $config->get('sidebar_background');
        $cssVars['--card-background'] = $config->get('card_background');
        
        // Convert to CSS format
        $cssString = ":root {\n";
        foreach ($cssVars as $var => $value) {
            $cssString .= "    {$var}: {$value};\n";
        }
        $cssString .= "}\n";
        
        return $cssString;
    }

    private function generateColorShades(string $hex): array
    {
        // Remove # if present
        $hex = ltrim($hex, '#');
        
        // Convert to RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        $shades = [];
        
        // Generate lighter shades
        for ($i = 50; $i <= 500; $i += 50) {
            $factor = 1 + (($i - 500) / 500 * 0.9);
            $newR = min(255, round($r * $factor));
            $newG = min(255, round($g * $factor));
            $newB = min(255, round($b * $factor));
            $shades[$i] = sprintf('#%02x%02x%02x', $newR, $newG, $newB);
        }
        
        // Base color
        $shades[500] = "#{$hex}";
        
        // Generate darker shades
        for ($i = 600; $i <= 950; $i += 50) {
            $factor = 1 - (($i - 500) / 500 * 0.8);
            $newR = round($r * $factor);
            $newG = round($g * $factor);
            $newB = round($b * $factor);
            $shades[$i] = sprintf('#%02x%02x%02x', $newR, $newG, $newB);
        }
        
        return $shades;
    }

    public function getFilamentColors(): array
    {
        $config = $this->getConfig();
        
        return [
            'primary' => $config->get('primary_color'),
            'secondary' => $config->get('secondary_color'), 
            'success' => $config->get('success_color'),
            'warning' => $config->get('warning_color'),
            'danger' => $config->get('danger_color'),
            'info' => $config->get('info_color'),
        ];
    }

    public function resetToDefaults(): bool
    {
        try {
            LandingPageSetting::whereIn('key', array_keys($this->defaultConfig))->delete();
            Cache::forget('theme_config');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
