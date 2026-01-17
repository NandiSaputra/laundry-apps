<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    /**
     * Get a setting value by key.
     */
    public function get(string $key, $default = null)
    {
        return Cache::rememberForever("setting.{$key}", function () use ($key, $default) {
            $setting = Setting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set/Update a setting value.
     */
    public function set(string $key, $value): void
    {
        Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting.{$key}");
    }

    /**
     * Get all settings as an associative array.
     */
    public function getAll(): array
    {
        return Setting::pluck('value', 'key')->toArray();
    }

    /**
     * Update multiple settings.
     */
    public function updateSettings(array $settings): void
    {
        foreach ($settings as $key => $value) {
            if ($value !== null) {
                $this->set($key, $value);
            }
        }
    }
}
