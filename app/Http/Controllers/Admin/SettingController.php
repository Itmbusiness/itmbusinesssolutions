<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /** key => [label, group, hint] */
    private const FIELDS = [
        'contact.phone' => ['Phone number', 'contact', 'Shown in the top bar of every public page.'],
        'contact.whatsapp' => ['WhatsApp number', 'contact', 'Used for the WhatsApp buttons. Digits only works best.'],
        'contact.email' => ['Email address', 'contact', null],
        'contact.hours' => ['Office hours', 'contact', null],
        'contact.address' => ['Address', 'contact', null],
        'site.tagline' => ['Tagline', 'site', 'Appears under the logo and in the footer.'],
        'site.safety_note' => ['Safety note', 'site', 'The warning shown in the footer and on forms.'],
    ];

    public function edit(): View
    {
        return view('pages.admin.settings', [
            'fields' => self::FIELDS,
            'values' => Setting::values(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'settings' => ['array'],
            'settings.*' => ['nullable', 'string', 'max:500'],
        ]);

        foreach ($validated['settings'] ?? [] as $key => $value) {
            if (! array_key_exists($key, self::FIELDS)) {
                continue;
            }

            Setting::put($key, $value, self::FIELDS[$key][1]);
        }

        return back()->with('status', 'Settings saved.');
    }
}
