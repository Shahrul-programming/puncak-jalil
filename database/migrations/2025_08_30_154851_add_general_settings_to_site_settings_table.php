<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            // General Site Settings
            $table->string('site_name')->nullable()->after('id');
            $table->text('site_description')->nullable()->after('site_name');
            $table->string('site_logo')->nullable()->after('site_description');
            $table->string('site_favicon')->nullable()->after('site_logo');
            
            // Business Information
            $table->string('business_address')->nullable()->after('contact_email');
            $table->string('business_hours')->nullable()->after('business_address');
            $table->string('whatsapp_number')->nullable()->after('business_hours');
            
            // Social Media Links
            $table->string('facebook_url')->nullable()->after('whatsapp_number');
            $table->string('instagram_url')->nullable()->after('facebook_url');
            $table->string('twitter_url')->nullable()->after('instagram_url');
            $table->string('youtube_url')->nullable()->after('twitter_url');
            
            // Email Settings
            $table->string('smtp_host')->nullable()->after('youtube_url');
            $table->string('smtp_port')->nullable()->after('smtp_host');
            $table->string('smtp_username')->nullable()->after('smtp_port');
            $table->string('smtp_password')->nullable()->after('smtp_username');
            $table->string('smtp_encryption')->nullable()->after('smtp_password');
            $table->string('mail_from_address')->nullable()->after('smtp_encryption');
            $table->string('mail_from_name')->nullable()->after('mail_from_address');
            
            // File Upload Settings
            $table->integer('max_upload_size')->default(5120)->after('mail_from_name'); // KB
            $table->json('allowed_file_types')->nullable()->after('max_upload_size');
            
            // Security Settings
            $table->integer('max_login_attempts')->default(5)->after('allowed_file_types');
            $table->integer('session_timeout')->default(120)->after('max_login_attempts'); // minutes
            $table->boolean('require_email_verification')->default(true)->after('session_timeout');
            
            // Maintenance
            $table->boolean('maintenance_mode')->default(false)->after('require_email_verification');
            $table->text('maintenance_message')->nullable()->after('maintenance_mode');
            
            // SEO Settings
            $table->text('meta_description')->nullable()->after('maintenance_message');
            $table->text('meta_keywords')->nullable()->after('meta_description');
            $table->string('google_analytics_id')->nullable()->after('meta_keywords');
            $table->string('google_maps_api_key')->nullable()->after('google_analytics_id');
            
            // System Settings
            $table->string('timezone')->default('Asia/Kuala_Lumpur')->after('google_maps_api_key');
            $table->string('date_format')->default('d/m/Y')->after('timezone');
            $table->string('time_format')->default('H:i')->after('date_format');
            $table->string('currency')->default('MYR')->after('time_format');
            $table->string('currency_symbol')->default('RM')->after('currency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'site_name', 'site_description', 'site_logo', 'site_favicon',
                'business_address', 'business_hours', 'whatsapp_number',
                'facebook_url', 'instagram_url', 'twitter_url', 'youtube_url',
                'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption',
                'mail_from_address', 'mail_from_name',
                'max_upload_size', 'allowed_file_types',
                'max_login_attempts', 'session_timeout', 'require_email_verification',
                'maintenance_mode', 'maintenance_message',
                'meta_description', 'meta_keywords', 'google_analytics_id', 'google_maps_api_key',
                'timezone', 'date_format', 'time_format', 'currency', 'currency_symbol'
            ]);
        });
    }
};
