<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    /**
     * Get or set a setting value
     */
    function setting($key = null, $default = null)
    {
        if (is_null($key)) {
            return Setting::getAllSettings();
        }
        
        if (is_array($key)) {
            return Setting::updateSettings($key);
        }
        
        return Setting::get($key, $default);
    }
}

if (!function_exists('setting_exists')) {
    /**
     * Check if a setting exists
     */
    function setting_exists($key)
    {
        return Setting::has($key);
    }
}

if (!function_exists('setting_group')) {
    /**
     * Get all settings in a group
     */
    function setting_group($group)
    {
        return Setting::getGroup($group);
    }
}