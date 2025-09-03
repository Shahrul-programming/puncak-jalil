<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class SiteSettingSeeder extends Seeder
{
    public function run()
    {
        SiteSetting::updateOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Puncak Jalil Community',
                'site_description' => 'Your local community platform',
                'contact_email' => 'info@puncakjalil.com',
                'contact_phone' => '+60 12-345 6789',
                'timezone' => 'Asia/Kuala_Lumpur',
                'currency' => 'MYR',
                'currency_symbol' => 'RM',
                'business_address' => 'Puncak Jalil, Selangor',
                'business_hours' => 'Mon-Fri 9am-6pm',
                'facebook_url' => 'https://facebook.com/puncakjalil',
                'instagram_url' => 'https://instagram.com/puncakjalil',
                'smtp_host' => 'smtp.mailtrap.io',
                'smtp_port' => '2525',
                'smtp_username' => 'username',
                'smtp_password' => 'password',
                'smtp_encryption' => 'tls',
                'mail_from_address' => 'info@puncakjalil.com',
                'mail_from_name' => 'Puncak Jalil',
                'max_upload_size' => 5120,
                'allowed_file_types' => json_encode(['jpg','jpeg','png','pdf','docx']),
                'max_login_attempts' => 5,
                'session_timeout' => 120,
                'require_email_verification' => true,
                'maintenance_mode' => false,
                'maintenance_message' => null,
                'meta_description' => 'Puncak Jalil Community Portal',
                'meta_keywords' => 'puncak jalil, community, portal',
                'google_analytics_id' => null,
                'google_maps_api_key' => null,
                'date_format' => 'd/m/Y',
                'time_format' => 'H:i',
            ]
        );
    }
}
