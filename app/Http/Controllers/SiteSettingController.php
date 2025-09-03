<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    public function edit()
    {
        $setting = SiteSetting::first();
        return view('admin.site_setting_edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'headline' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'headline_font_size' => 'nullable|string|max:50',
            'headline_color' => 'nullable|string|max:50',
            'headline_alignment' => 'nullable|string|max:50',
            'background_color' => 'nullable|string|max:100',
            'contact_phone' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email|max:100',
            'headline_image' => 'nullable|image|max:2048',
        ]);

        $setting = SiteSetting::first() ?? new SiteSetting();
        
        // Update all text fields
        $setting->headline = $request->headline;
        $setting->description = $request->description;
        $setting->headline_font_size = $request->headline_font_size ?? 'text-4xl';
        $setting->headline_color = $request->headline_color ?? 'text-gray-900';
        $setting->headline_alignment = $request->headline_alignment ?? 'text-center';
        $setting->background_color = $request->background_color;
        $setting->contact_phone = $request->contact_phone;
        $setting->contact_email = $request->contact_email;

        // Handle image upload
        if ($request->hasFile('headline_image')) {
            if ($setting->headline_image) {
                Storage::disk('public')->delete($setting->headline_image);
            }
            $setting->headline_image = $request->file('headline_image')->store('headline_images', 'public');
        }

        $setting->save();
        return redirect()->back()->with('success', 'Berjaya kemaskini site settings!');
    }
}
