<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::query()->pluck('value','key'); // ['site_name' => '...', ...]
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name'              => ['required','string','max:120'],
            'store_email'            => ['nullable','email'],
            'support_phone'          => ['nullable','string','max:40'],
            'currency'               => ['required','string','max:8'],
            'tax_rate'               => ['nullable','numeric','min:0','max:100'],
            'free_shipping_threshold'=> ['nullable','numeric','min:0'],
            'maintenance_mode'       => ['nullable','boolean'],
            'logo'                   => ['nullable','image','max:2048'],
        ]);

        foreach ([
            'site_name','store_email','support_phone','currency','tax_rate','free_shipping_threshold'
        ] as $key) {
            if (array_key_exists($key, $data)) {
                Setting::set($key, (string) $data[$key]);
            }
        }

        // maintenance_mode as boolean "1"/"0"
        Setting::set('maintenance_mode', $request->boolean('maintenance_mode') ? '1' : '0');

        // logo upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            Setting::set('logo_path', $path);
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
