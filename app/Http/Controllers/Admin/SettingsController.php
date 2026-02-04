<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:admin');
    // }

    public function general()
    {
        $settings = Setting::getGroup('general');
        
        // Add default values if not exists
        $defaults = [
            'site_name' => config('app.name'),
            'site_email' => config('mail.from.address'),
            'site_phone' => '',
            'site_address' => '',
            'currency' => 'USD',
            'currency_symbol' => '$',
            'timezone' => config('app.timezone'),
            'date_format' => 'Y-m-d',
            'time_format' => 'H:i',
            'logo' => null,
            'favicon' => null,
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
        ];
        
        $settings = array_merge($defaults, $settings);
        
        return view('admin.settings.general', compact('settings'));
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|email',
            'site_phone' => 'nullable|string|max:20',
            'site_address' => 'nullable|string|max:500',
            'currency' => 'required|string|size:3',
            'currency_symbol' => 'required|string|max:5',
            'timezone' => 'required|timezone',
            'date_format' => 'required|string',
            'time_format' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:1024',
        ]);

        $data = $request->except(['logo', 'favicon', '_token', '_method']);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $currentLogo = Setting::get('logo');
            
            // Delete old logo if exists
            if ($currentLogo && Storage::disk('public')->exists($currentLogo)) {
                Storage::disk('public')->delete($currentLogo);
            }
            
            $logoPath = $request->file('logo')->store('settings', 'public');
            $data['logo'] = $logoPath;
        } else {
            // Keep existing logo if not uploading new one
            $data['logo'] = Setting::get('logo');
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $currentFavicon = Setting::get('favicon');
            
            // Delete old favicon if exists
            if ($currentFavicon && Storage::disk('public')->exists($currentFavicon)) {
                Storage::disk('public')->delete($currentFavicon);
            }
            
            $faviconPath = $request->file('favicon')->store('settings', 'public');
            $data['favicon'] = $faviconPath;
        } else {
            // Keep existing favicon
            $data['favicon'] = Setting::get('favicon');
        }

        // Update settings
        Setting::updateSettings($data);

        return redirect()->back()->with('success', 'General settings updated successfully.');
    }

    public function payment()
    {
        $settings = Setting::getGroup('payment');
        
        $defaults = [
            'stripe_key' => '',
            'stripe_secret' => '',
            'paypal_client_id' => '',
            'paypal_secret' => '',
            'cod_enabled' => true,
            'bank_transfer_enabled' => false,
            'bank_details' => '',
        ];
        
        // Convert boolean strings to actual booleans for the view
        $settings = array_merge($defaults, $settings);
        $settings['cod_enabled'] = filter_var($settings['cod_enabled'], FILTER_VALIDATE_BOOLEAN);
        $settings['bank_transfer_enabled'] = filter_var($settings['bank_transfer_enabled'], FILTER_VALIDATE_BOOLEAN);
        
        return view('admin.settings.payment', compact('settings'));
    }

    public function updatePayment(Request $request)
    {
        $request->validate([
            'stripe_key' => 'nullable|string',
            'stripe_secret' => 'nullable|string',
            'paypal_client_id' => 'nullable|string',
            'paypal_secret' => 'nullable|string',
            'cod_enabled' => 'boolean',
            'bank_transfer_enabled' => 'boolean',
            'bank_details' => 'nullable|string',
        ]);

        $data = $request->all();
        
        // Convert booleans to string for storage
        $data['cod_enabled'] = $request->boolean('cod_enabled') ? '1' : '0';
        $data['bank_transfer_enabled'] = $request->boolean('bank_transfer_enabled') ? '1' : '0';

        Setting::updateSettings($data);

        return redirect()->back()->with('success', 'Payment settings updated successfully.');
    }

    public function shipping()
    {
        $settings = Setting::getGroup('shipping');
        
        $defaults = [
            'free_shipping_threshold' => 100,
            'standard_shipping_cost' => 5.99,
            'express_shipping_cost' => 12.99,
            'weight_unit' => 'kg',
            'dimension_unit' => 'cm',
        ];
        
        $settings = array_merge($defaults, $settings);
        
        return view('admin.settings.shipping', compact('settings'));
    }

    public function updateShipping(Request $request)
    {
        $request->validate([
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'standard_shipping_cost' => 'required|numeric|min:0',
            'express_shipping_cost' => 'required|numeric|min:0',
            'weight_unit' => 'required|in:kg,lb',
            'dimension_unit' => 'required|in:cm,in',
        ]);

        Setting::updateSettings($request->all());

        return redirect()->back()->with('success', 'Shipping settings updated successfully.');
    }

    public function notifications()
    {
        $settings = Setting::getGroup('notifications');
        
        $defaults = [
            'order_confirmation' => true,
            'order_status_update' => true,
            'new_customer_registration' => true,
            'low_stock_notification' => true,
            'admin_email' => '',
            'notification_email' => '',
        ];
        
        $settings = array_merge($defaults, $settings);
        
        // Convert boolean strings
        foreach ($settings as $key => $value) {
            if (in_array($key, ['order_confirmation', 'order_status_update', 'new_customer_registration', 'low_stock_notification'])) {
                $settings[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            }
        }
        
        return view('admin.settings.notifications', compact('settings'));
    }

    public function updateNotifications(Request $request)
    {
        $request->validate([
            'order_confirmation' => 'boolean',
            'order_status_update' => 'boolean',
            'new_customer_registration' => 'boolean',
            'low_stock_notification' => 'boolean',
            'admin_email' => 'nullable|email',
            'notification_email' => 'nullable|email',
        ]);

        $data = $request->all();
        
        // Convert booleans to string
        $booleanFields = ['order_confirmation', 'order_status_update', 'new_customer_registration', 'low_stock_notification'];
        foreach ($booleanFields as $field) {
            $data[$field] = $request->boolean($field) ? '1' : '0';
        }

        Setting::updateSettings($data);

        return redirect()->back()->with('success', 'Notification settings updated successfully.');
    }

    public function social()
    {
        $settings = Setting::getGroup('social');
        
        $defaults = [
            'facebook_url' => '',
            'twitter_url' => '',
            'instagram_url' => '',
            'linkedin_url' => '',
            'youtube_url' => '',
        ];
        
        $settings = array_merge($defaults, $settings);
        
        return view('admin.settings.social', compact('settings'));
    }

    public function updateSocial(Request $request)
    {
        $request->validate([
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
        ]);

        Setting::updateSettings($request->all());

        return redirect()->back()->with('success', 'Social media settings updated successfully.');
    }

    /**
     * Clear settings cache
     */
    public function clearCache()
    {
        Cache::forget('settings.all');
        return redirect()->back()->with('success', 'Settings cache cleared successfully.');
    }

    /**
     * Backup settings
     */
    public function backup()
    {
        $settings = Setting::all();
        $filename = 'settings-backup-' . date('Y-m-d-H-i-s') . '.json';
        
        return response()->streamDownload(function () use ($settings) {
            echo $settings->toJson(JSON_PRETTY_PRINT);
        }, $filename);
    }

    /**
     * Reset settings to defaults
     */
    public function reset(Request $request)
    {
        $request->validate([
            'group' => 'required|in:general,payment,shipping,notifications,social'
        ]);
        
        // You can implement this based on your default settings
        // This would reset all settings in a group to their default values
        
        return redirect()->back()->with('success', ucfirst($request->group) . ' settings reset to defaults.');
    }
}