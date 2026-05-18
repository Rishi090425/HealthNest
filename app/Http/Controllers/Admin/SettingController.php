<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected array $keys = [
        'site_name', 'site_tagline', 'contact_email', 'contact_phone',
        'appointment_reminder_hours', 'currency', 'logo_path',
        'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password',
        'appointment_reminder_template', 'cancellation_template',
        'upi_id',
    ];

    public function index()
    {
        $settings = [];
        foreach ($this->keys as $key) {
            $settings[$key] = Setting::get($key, '');
        }
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name'  => 'required|string|max:100',
            'currency'   => 'required|string|size:3',
        ]);

        foreach ($this->keys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('branding', 'public');
            Setting::set('logo_path', $path);
        }

        AuditLog::record('Updated System Settings');
        return back()->with('success', 'Settings saved successfully.');
    }
}
