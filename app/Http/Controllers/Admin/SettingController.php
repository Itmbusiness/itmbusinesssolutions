<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\PublicMedia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /** key => [label, group, hint] */
    private const FIELDS = [
        'media.registration' => ['Registration image', 'public images', 'Local image path, e.g. images/public/registration-v1.webp. Leave blank for the generated default.'],
        'media.consultation' => ['Consultation image', 'public images', 'Local image path. Leave blank for the generated default.'],
        'media.requirements' => ['Requirements image', 'public images', 'Local image path. Leave blank for the generated default.'],
        'media.quotation' => ['Quotation image', 'public images', 'Local image path. Leave blank for the generated default.'],
        'media.resources' => ['Resources image', 'public images', 'Local image path. Leave blank for the generated default.'],
        'media.tax' => ['Tax image', 'public images', 'Local image path. Leave blank for the generated default.'],
        'media.employer' => ['Employer image', 'public images', 'Local image path. Leave blank for the generated default.'],
        'media.branding' => ['Business documents image', 'public images', 'Local image path. Leave blank for the generated default.'],
        'media.licensing' => ['Licensing image', 'public images', 'Local image path. Leave blank for the generated default.'],
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
            'fields' => array_filter(self::FIELDS, fn (array $field) => $field[1] !== 'public images'),
            'imageSubjects' => PublicMedia::SUBJECTS,
            'values' => Setting::values(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'settings' => ['array'],
            'settings.*' => ['nullable', 'string', 'max:500'],
            'settings.media\\.registration' => ['nullable', 'regex:~^images/[a-zA-Z0-9/_-]+\.(webp|png|jpe?g|avif)$~'],
            'settings.media\\.consultation' => ['nullable', 'regex:~^images/[a-zA-Z0-9/_-]+\.(webp|png|jpe?g|avif)$~'],
            'settings.media\\.requirements' => ['nullable', 'regex:~^images/[a-zA-Z0-9/_-]+\.(webp|png|jpe?g|avif)$~'],
            'settings.media\\.quotation' => ['nullable', 'regex:~^images/[a-zA-Z0-9/_-]+\.(webp|png|jpe?g|avif)$~'],
            'settings.media\\.resources' => ['nullable', 'regex:~^images/[a-zA-Z0-9/_-]+\.(webp|png|jpe?g|avif)$~'],
            'settings.media\\.tax' => ['nullable', 'regex:~^images/[a-zA-Z0-9/_-]+\.(webp|png|jpe?g|avif)$~'],
            'settings.media\\.employer' => ['nullable', 'regex:~^images/[a-zA-Z0-9/_-]+\.(webp|png|jpe?g|avif)$~'],
            'settings.media\\.branding' => ['nullable', 'regex:~^images/[a-zA-Z0-9/_-]+\.(webp|png|jpe?g|avif)$~'],
            'settings.media\\.licensing' => ['nullable', 'regex:~^images/[a-zA-Z0-9/_-]+\.(webp|png|jpe?g|avif)$~'],
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
