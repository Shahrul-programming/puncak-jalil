<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SiteSetting extends Model
{
    use HasFactory;
    
    protected $fillable = [
        // Homepage Settings (existing)
        'headline',
        'headline_font_size',
        'headline_color', 
        'headline_alignment',
        'description',
        'background_color',
        'contact_phone',
        'contact_email',
        'headline_image',
        
        // New General Settings
        'site_name',
        'site_description',
        'site_logo',
        'site_favicon',
        'business_address',
        'business_hours', 
        'whatsapp_number',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'youtube_url',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'mail_from_address',
        'mail_from_name',
        'max_upload_size',
        'allowed_file_types',
        'max_login_attempts',
        'session_timeout',
        'require_email_verification',
        'maintenance_mode',
        'maintenance_message',
        'meta_description',
        'meta_keywords',
        'google_analytics_id',
        'google_maps_api_key',
        'timezone',
        'date_format',
        'time_format',
        'currency',
        'currency_symbol'
    ];

    protected $casts = [
        'allowed_file_types' => 'array',
        'require_email_verification' => 'boolean',
        'maintenance_mode' => 'boolean',
        'max_upload_size' => 'integer',
        'max_login_attempts' => 'integer',
        'session_timeout' => 'integer',
    ];

    /**
     * Get the single settings instance (singleton pattern)
     */
    public static function getSettings()
    {
        return static::first() ?: static::create([
            'site_name' => 'Puncak Jalil Community',
            'site_description' => 'Your local community platform',
            'contact_email' => 'info@puncakjalil.com',
            'contact_phone' => '+60 12-345 6789',
            'timezone' => 'Asia/Kuala_Lumpur',
            'currency' => 'MYR',
            'currency_symbol' => 'RM',
        ]);
    }

    /**
     * Update settings
     */
    public static function updateSettings(array $data)
    {
        $settings = static::getSettings();
        return $settings->update($data);
    }

    /**
     * Get specific setting value
     */
    public static function getValue($key, $default = null)
    {
        $settings = static::getSettings();
        return $settings->$key ?? $default;
    }

    /**
     * Check if maintenance mode is enabled
     */
    public static function isMaintenanceMode()
    {
        return static::getValue('maintenance_mode', false);
    }

    /**
     * Get social media links
     */
    public static function getSocialLinks()
    {
        $settings = static::getSettings();
        return [
            'facebook' => $settings->facebook_url,
            'instagram' => $settings->instagram_url, 
            'twitter' => $settings->twitter_url,
            'youtube' => $settings->youtube_url,
        ];
    }
}
