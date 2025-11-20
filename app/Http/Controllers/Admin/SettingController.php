<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function edit()
    {
        // පළවෙනි record එක ගන්නවා (අපි seeder එකෙන් හැදුවානේ)
        $settings = SchoolSetting::first();
        return view('admin.settings.edit', compact('settings'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
            'school_address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Image validation
        ]);

        $settings = SchoolSetting::first();

        // Data update කිරීම
        $settings->school_name = $request->school_name;
        $settings->school_address = $request->school_address;
        $settings->phone = $request->phone;
        $settings->email = $request->email;

        // Logo Upload Logic
        if ($request->hasFile('logo')) {

            // පරණ logo එකක් තියෙනවා නම් delete කරන්න
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }

            // අලුත් logo එක 'public/logos' folder එකට save කරනවා
            $path = $request->file('logo')->store('logos', 'public');
            $settings->logo_path = $path;
        }

        $settings->save();

        return back()->with('success', 'School settings updated successfully.');
    }
}
