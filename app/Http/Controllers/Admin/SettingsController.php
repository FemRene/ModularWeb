<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function toggleRegistration(Request $request)
    {
        Setting::set('registration_enabled', $request->has('registration_enabled') ? '1' : '0');

        return redirect()->back()->with('success', 'Settings saved.');
    }

    public function saveAllSettings(Request $request)
    {
        // Get all inputs except _token and other unwanted fields
        $inputs = $request->except(['_token', '_method']);

        foreach ($inputs as $key => $value) {
            \App\Models\Setting::set($key, $value);
        }

        return redirect()->back()->with('success', 'Settings saved!');
    }

    public static function get($key, $default = null)
    {
        $setting = \App\Models\Setting::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, $value): void
    {
        Setting::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public function show()
    {
        $settings = \App\Helpers\SettingsSpecLoader::getAllSettings();
        return view('admin.settings.index', compact('settings'));
    }

    public function save(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => is_array($value) ? json_encode($value) : $value]
            );
        }

        return back()->with('success', 'Settings saved.');
    }
}
