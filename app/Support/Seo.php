<?php

namespace App\Support;

use App\Models\Setting;

class Seo
{
    public static function url(string $url): string
    {
        if (! app()->isProduction()) {
            return $url;
        }

        return rtrim(config('seo.public_url'), '/').(parse_url($url, PHP_URL_PATH) ?: '/');
    }

    /** @param array<string, mixed>|null $pageSchema
     * @return array<string, mixed>
     */
    public static function graph(?array $pageSchema = null): array
    {
        $home = self::url(url('/'));
        $organisation = [
            '@type' => 'Organization',
            '@id' => $home.'#organization',
            'name' => config('app.name'),
            'url' => $home,
            'logo' => self::url(asset('images/itm-logo.png')),
            'areaServed' => ['@type' => 'Country', 'name' => 'Zimbabwe'],
        ];

        foreach (['telephone' => 'contact.phone', 'email' => 'contact.email', 'address' => 'contact.address'] as $property => $key) {
            $value = Setting::get($key);
            if (filled($value) && ! str_contains($value, '[')) {
                $organisation[$property] = $value;
            }
        }

        $graph = [$organisation];
        if ($pageSchema !== null) {
            if (isset($pageSchema['@graph'])) {
                $graph = array_merge($graph, $pageSchema['@graph']);
            } else {
                unset($pageSchema['@context']);
                $graph[] = $pageSchema;
            }
        }

        return ['@context' => 'https://schema.org', '@graph' => $graph];
    }
}
