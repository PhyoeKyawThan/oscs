<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group', 'type', 'description'];
    
    public $timestamps = true;
    
    /**
     * Get all settings from cache or database
     */
    public static function getAllSettings()
    {
        return Cache::remember('settings.all', 3600, function () {
            return self::all()->pluck('value', 'key')->toArray();
        });
    }
    
    /**
     * Get setting by key
     */
    public static function get($key, $default = null)
    {
        $settings = self::getAllSettings();
        return $settings[$key] ?? $default;
    }
    
    /**
     * Set a setting value
     */
    public static function set($key, $value)
    {
        $setting = self::where('key', $key)->first();
        
        if ($setting) {
            $setting->update(['value' => $value]);
        } else {
            $setting = self::create([
                'key' => $key,
                'value' => $value,
                'group' => 'general',
                'type' => 'string'
            ]);
        }
        
        // Clear cache
        Cache::forget('settings.all');
        
        return $setting;
    }
    
    /**
     * Get settings by group
     */
    public static function getGroup($group)
    {
        return self::where('group', $group)->pluck('value', 'key')->toArray();
    }
    
    /**
     * Update multiple settings at once
     */
    public static function updateSettings(array $data)
    {
        foreach ($data as $key => $value) {
            self::set($key, $value);
        }
        
        return true;
    }
    
    /**
     * Check if setting exists
     */
    public static function has($key)
    {
        return isset(self::getAllSettings()[$key]);
    }
}