<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class LandingPageSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description'
    ];

    public static function get($key, $default = null)
    {
        return Cache::remember("landing_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();

            if (!$setting) {
                return $default;
            }

            return match ($setting->type) {
                'boolean' => (bool) $setting->value,
                'json' => json_decode($setting->value, true),
                'integer' => (int) $setting->value,
                'float' => (float) $setting->value,
                default => $setting->value
            };
        });
    }

    public static function set($key, $value, $type = 'text')
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'type' => $type
            ]
        );

        Cache::forget("landing_setting_{$key}");
        return $setting;
    }

    public static function getByGroup($group)
    {
        return Cache::remember("landing_settings_group_{$group}", 3600, function () use ($group) {
            return self::where('group', $group)->pluck('value', 'key')->toArray();
        });
    }

    public static function clearCache()
    {
        $keys = self::pluck('key');
        foreach ($keys as $key) {
            Cache::forget("landing_setting_{$key}");
        }

        $groups = self::distinct()->pluck('group');
        foreach ($groups as $group) {
            Cache::forget("landing_settings_group_{$group}");
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            self::clearCache();
        });

        static::deleted(function () {
            self::clearCache();
        });
    }
}
