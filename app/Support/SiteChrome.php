<?php

namespace App\Support;

use App\Models\ServiceCategory;
use App\Models\Setting;
use Illuminate\Support\Collection;

/**
 * Shared data for the public header and footer.
 *
 * Injected into the layout with `@inject` rather than supplied by a view
 * composer: `<x-layouts::public>` is a namespaced anonymous component, and
 * composers do not fire for those.
 */
class SiteChrome
{
    /** @var Collection<int, ServiceCategory>|null */
    private ?Collection $categories = null;

    /**
     * @return Collection<int, ServiceCategory>
     */
    public function categories(): Collection
    {
        return $this->categories ??= ServiceCategory::published()->ordered()->get();
    }

    public function setting(string $key, ?string $default = null): ?string
    {
        return Setting::get($key, $default);
    }

    public function has(string $key): bool
    {
        return filled(Setting::get($key));
    }
}
