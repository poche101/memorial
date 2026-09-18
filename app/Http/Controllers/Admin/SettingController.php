<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    protected array $keys = [
        'site_name', 'logo_path', 'primary_color', 'accent_color',
        'contact_email', 'facebook_url', 'instagram_url', 'twitter_url',
        'candle_lighting_enabled', 'submission_requires_consent',
        'max_upload_size_mb', 'notify_on_new_tribute', 'notify_on_new_contact',
    ];

    public function edit(): View
    {
        $settings = collect($this->keys)->mapWithKeys(fn ($key) => [$key => Setting::get($key)]);

        return view('admin.settings.edit', ['settings' => $settings]);
    }

    public function update(Request $request): RedirectResponse
    {
        foreach ($this->keys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }

        AuditLog::record('settings.updated');

        return back()->with('status', 'Settings saved.');
    }
}
