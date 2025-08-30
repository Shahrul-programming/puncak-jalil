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
            'headline_image' => 'nullable|image|max:2048',
        ]);

        $setting = SiteSetting::first() ?? new SiteSetting();
        $setting->headline = $request->headline;

        if ($request->hasFile('headline_image')) {
            if ($setting->headline_image) {
                Storage::delete($setting->headline_image);
            }
            $setting->headline_image = $request->file('headline_image')->store('headline_images', 'public');
        }

        $setting->save();
        return redirect()->back()->with('success', 'Berjaya kemaskini headline!');
    }
}
