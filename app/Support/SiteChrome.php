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

    /** @var Collection<int, ServiceCategory>|null */
    private ?Collection $categoriesWithServices = null;

    /**
     * @return Collection<int, ServiceCategory>
     */
    public function categories(): Collection
    {
        return $this->categories ??= ServiceCategory::published()->ordered()->get();
    }

    /**
     * Categories with a handful of their services preloaded, for the header's
     * "Services" mega menu. Capped per category so the panel stays a fixed,
     * predictable size regardless of how many services a category grows to.
     *
     * @return Collection<int, ServiceCategory>
     */
    public function categoriesWithServices(int $perCategory = 4): Collection
    {
        return $this->categoriesWithServices ??= ServiceCategory::published()
            ->ordered()
            ->with(['services' => fn ($query) => $query->published()->orderBy('position')->orderBy('name')->limit($perCategory)])
            ->withCount(['services' => fn ($query) => $query->published()])
            ->get();
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
