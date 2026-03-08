<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $settings = Setting::all()->groupBy('type');

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'nullable',
            'settings.*.type' => 'required|in:string,boolean,integer,json',
            'settings.*.description' => 'nullable|string',
        ]);

        foreach ($validated['settings'] as $settingData) {
            Setting::set(
                $settingData['key'],
                $settingData['value'],
                $settingData['type'],
                $settingData['description'] ?? null
            );
        }

        return back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Create new setting
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:settings,key',
            'value' => 'nullable',
            'type' => 'required|in:string,boolean,integer,json',
            'description' => 'nullable|string',
        ]);

        Setting::create($validated);

        return back()->with('success', 'Setting created successfully.');
    }

    /**
     * Delete setting
     */
    public function destroy(Setting $setting)
    {
        $setting->delete();

        return back()->with('success', 'Setting deleted successfully.');
    }
}