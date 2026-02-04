<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->string('type')->default('string'); // string, boolean, integer, json, image
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index(['group', 'key']);
        });
        
        // Insert default settings
        $this->insertDefaultSettings();
    }
    
    private function insertDefaultSettings()
    {
        $settings = [
            // General Settings
            ['key' => 'site_name', 'value' => 'Laravel Shop', 'group' => 'general', 'type' => 'string'],
            ['key' => 'site_email', 'value' => 'admin@example.com', 'group' => 'general', 'type' => 'string'],
            ['key' => 'site_phone', 'value' => '', 'group' => 'general', 'type' => 'string'],
            ['key' => 'site_address', 'value' => '', 'group' => 'general', 'type' => 'text'],
            ['key' => 'currency', 'value' => 'USD', 'group' => 'general', 'type' => 'string'],
            ['key' => 'currency_symbol', 'value' => '$', 'group' => 'general', 'type' => 'string'],
            ['key' => 'timezone', 'value' => 'UTC', 'group' => 'general', 'type' => 'string'],
            ['key' => 'date_format', 'value' => 'Y-m-d', 'group' => 'general', 'type' => 'string'],
            ['key' => 'time_format', 'value' => 'H:i', 'group' => 'general', 'type' => 'string'],
            ['key' => 'logo', 'value' => null, 'group' => 'general', 'type' => 'image'],
            ['key' => 'favicon', 'value' => null, 'group' => 'general', 'type' => 'image'],
            ['key' => 'meta_title', 'value' => '', 'group' => 'general', 'type' => 'string'],
            ['key' => 'meta_description', 'value' => '', 'group' => 'general', 'type' => 'text'],
            ['key' => 'meta_keywords', 'value' => '', 'group' => 'general', 'type' => 'text'],
            
            // Payment Settings
            ['key' => 'stripe_key', 'value' => '', 'group' => 'payment', 'type' => 'string'],
            ['key' => 'stripe_secret', 'value' => '', 'group' => 'payment', 'type' => 'string'],
            ['key' => 'paypal_client_id', 'value' => '', 'group' => 'payment', 'type' => 'string'],
            ['key' => 'paypal_secret', 'value' => '', 'group' => 'payment', 'type' => 'string'],
            ['key' => 'cod_enabled', 'value' => '1', 'group' => 'payment', 'type' => 'boolean'],
            ['key' => 'bank_transfer_enabled', 'value' => '0', 'group' => 'payment', 'type' => 'boolean'],
            ['key' => 'bank_details', 'value' => '', 'group' => 'payment', 'type' => 'text'],
            
            // Shipping Settings
            ['key' => 'free_shipping_threshold', 'value' => '100', 'group' => 'shipping', 'type' => 'decimal'],
            ['key' => 'standard_shipping_cost', 'value' => '5.99', 'group' => 'shipping', 'type' => 'decimal'],
            ['key' => 'express_shipping_cost', 'value' => '12.99', 'group' => 'shipping', 'type' => 'decimal'],
            ['key' => 'weight_unit', 'value' => 'kg', 'group' => 'shipping', 'type' => 'string'],
            ['key' => 'dimension_unit', 'value' => 'cm', 'group' => 'shipping', 'type' => 'string'],
            
            // Notification Settings
            ['key' => 'order_confirmation', 'value' => '1', 'group' => 'notifications', 'type' => 'boolean'],
            ['key' => 'order_status_update', 'value' => '1', 'group' => 'notifications', 'type' => 'boolean'],
            ['key' => 'new_customer_registration', 'value' => '1', 'group' => 'notifications', 'type' => 'boolean'],
            ['key' => 'low_stock_notification', 'value' => '1', 'group' => 'notifications', 'type' => 'boolean'],
            ['key' => 'admin_email', 'value' => '', 'group' => 'notifications', 'type' => 'string'],
            ['key' => 'notification_email', 'value' => '', 'group' => 'notifications', 'type' => 'string'],
            
            // Social Media
            ['key' => 'facebook_url', 'value' => '', 'group' => 'social', 'type' => 'string'],
            ['key' => 'twitter_url', 'value' => '', 'group' => 'social', 'type' => 'string'],
            ['key' => 'instagram_url', 'value' => '', 'group' => 'social', 'type' => 'string'],
            ['key' => 'linkedin_url', 'value' => '', 'group' => 'social', 'type' => 'string'],
            ['key' => 'youtube_url', 'value' => '', 'group' => 'social', 'type' => 'string'],
        ];
        
        foreach ($settings as $setting) {
            DB::table('settings')->insert($setting);
        }
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};