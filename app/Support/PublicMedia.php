<?php

namespace App\Support;

use App\Models\Setting;

class PublicMedia
{
    public const SUBJECTS = [
        'registration' => 'Registration',
        'consultation' => 'Consultation',
        'requirements' => 'Requirements',
        'quotation' => 'Quotation',
        'resources' => 'Resources',
        'tax' => 'Tax',
        'employer' => 'Employer compliance',
        'branding' => 'Business documents',
        'licensing' => 'Licensing',
    ];

    public static function path(string $subject = 'registration'): ?string
    {
        $kind = array_key_exists($subject, self::SUBJECTS) ? $subject : match (true) {
            str_contains($subject, 'quotation'), str_contains($subject, 'quote'), str_contains($subject, 'process') => 'quotation',
            str_contains($subject, 'requirement') => 'requirements',
            str_contains($subject, 'licen') => 'licensing',
            str_contains($subject, 'nssa'), str_contains($subject, 'employer') => 'employer',
            str_contains($subject, 'brand'), str_contains($subject, 'profile') => 'branding',
            str_contains($subject, 'tax'), str_contains($subject, 'fiscal'), str_contains($subject, 'tin'), str_contains($subject, 'pay') => 'tax',
            str_contains($subject, 'about'), str_contains($subject, 'trust'), str_contains($subject, 'tender'), str_contains($subject, 'pbc') => 'consultation',
            str_contains($subject, 'resource'), str_contains($subject, 'document') => 'resources',
            default => 'registration',
        };

        $path = Setting::get('media.'.$kind);

        return $path === 'none' ? null : ($path ?: 'images/public/'.$kind.'-v1.webp');
    }
}
