<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    public function index()
    {
        $setting = SiteSetting::first();
        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:50',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            // ...tambah rules lain ikut column...
        ]);

        $setting = SiteSetting::first();
        if (!$setting) {
            $setting = new SiteSetting();
        }

        $setting->site_name = $request->site_name;
        $setting->site_description = $request->site_description;
        $setting->contact_email = $request->contact_email;
        $setting->contact_phone = $request->contact_phone;
        $setting->facebook_url = $request->facebook_url;
        $setting->instagram_url = $request->instagram_url;
        // ...tambah assignment lain ikut column...

        $setting->save();

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Settings updated successfully!');
    }

    public function reset()
    {
        // Reset to default values
        $this->seedDefaultSettings();
        
        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Settings reset to default values!');
    }

    private function seedDefaultSettings()
    {
        $defaultSettings = [
            // General Settings
            ['key' => 'site_name', 'value' => 'Puncak Jalil', 'type' => 'text', 'group' => 'general', 'label' => 'Site Name', 'description' => 'The name of your website'],
            ['key' => 'site_description', 'value' => 'Puncak Jalil Community Portal', 'type' => 'textarea', 'group' => 'general', 'label' => 'Site Description', 'description' => 'A brief description of your website'],
            ['key' => 'site_logo', 'value' => null, 'type' => 'file', 'group' => 'general', 'label' => 'Site Logo', 'description' => 'Upload your site logo'],
            ['key' => 'contact_email', 'value' => 'contact@puncakjalil.com', 'type' => 'email', 'group' => 'general', 'label' => 'Contact Email', 'description' => 'Main contact email address'],
            ['key' => 'contact_phone', 'value' => '+60 12-345 6789', 'type' => 'text', 'group' => 'general', 'label' => 'Contact Phone', 'description' => 'Main contact phone number'],
            ['key' => 'contact_address', 'value' => 'Puncak Jalil, Selangor, Malaysia', 'type' => 'textarea', 'group' => 'general', 'label' => 'Contact Address', 'description' => 'Physical address'],

            // Email Settings
            ['key' => 'smtp_host', 'value' => 'smtp.gmail.com', 'type' => 'text', 'group' => 'email', 'label' => 'SMTP Host', 'description' => 'SMTP server hostname'],
            ['key' => 'smtp_port', 'value' => '587', 'type' => 'number', 'group' => 'email', 'label' => 'SMTP Port', 'description' => 'SMTP server port'],
            ['key' => 'smtp_username', 'value' => '', 'type' => 'text', 'group' => 'email', 'label' => 'SMTP Username', 'description' => 'SMTP username'],
            ['key' => 'smtp_encryption', 'value' => 'tls', 'type' => 'select', 'group' => 'email', 'label' => 'SMTP Encryption', 'description' => 'SMTP encryption method', 'options' => ['tls' => 'TLS', 'ssl' => 'SSL', 'none' => 'None']],

            // Upload Settings
            ['key' => 'max_file_size', 'value' => '5120', 'type' => 'number', 'group' => 'upload', 'label' => 'Max File Size (KB)', 'description' => 'Maximum file size for uploads'],
            ['key' => 'max_image_width', 'value' => '1920', 'type' => 'number', 'group' => 'upload', 'label' => 'Max Image Width', 'description' => 'Maximum image width in pixels'],
            ['key' => 'max_image_height', 'value' => '1080', 'type' => 'number', 'group' => 'upload', 'label' => 'Max Image Height', 'description' => 'Maximum image height in pixels'],

            // Security Settings
            ['key' => 'max_login_attempts', 'value' => '5', 'type' => 'number', 'group' => 'security', 'label' => 'Max Login Attempts', 'description' => 'Maximum failed login attempts before lockout'],
            ['key' => 'login_timeout', 'value' => '15', 'type' => 'number', 'group' => 'security', 'label' => 'Login Timeout (minutes)', 'description' => 'Time before login lockout expires'],
            ['key' => 'session_timeout', 'value' => '120', 'type' => 'number', 'group' => 'security', 'label' => 'Session Timeout (minutes)', 'description' => 'User session timeout duration'],

            // Social Media
            ['key' => 'facebook_url', 'value' => '', 'type' => 'url', 'group' => 'social', 'label' => 'Facebook URL', 'description' => 'Facebook page URL'],
            ['key' => 'instagram_url', 'value' => '', 'type' => 'url', 'group' => 'social', 'label' => 'Instagram URL', 'description' => 'Instagram profile URL'],
            ['key' => 'whatsapp_number', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'WhatsApp Number', 'description' => 'WhatsApp contact number'],

            // Maintenance
            ['key' => 'maintenance_mode', 'value' => 'false', 'type' => 'boolean', 'group' => 'maintenance', 'label' => 'Maintenance Mode', 'description' => 'Enable maintenance mode'],
            ['key' => 'maintenance_message', 'value' => 'Site is under maintenance. Please check back later.', 'type' => 'textarea', 'group' => 'maintenance', 'label' => 'Maintenance Message', 'description' => 'Message to show during maintenance'],
        ];

        foreach ($defaultSettings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
