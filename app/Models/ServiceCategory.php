<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string|null $summary
 * @property string|null $body
 * @property array<int, string>|null $audience
 * @property string|null $seo_title
 * @property string|null $meta_description
 * @property string|null $primary_keyword
 * @property string|null $icon
 * @property int $position
 * @property bool $is_published
 * @property CarbonInterface|null $reviewed_on
 * @property CarbonInterface|null $next_review_date
 */
#[Fillable([
    'slug', 'name', 'summary', 'body', 'audience', 'seo_title', 'meta_description',
    'primary_keyword', 'icon', 'position', 'is_published', 'reviewed_on', 'next_review_date',
])]
class ServiceCategory extends Model
{
    protected function casts(): array
    {
        return [
            'audience' => 'array',
            'is_published' => 'boolean',
            'reviewed_on' => 'date',
            'next_review_date' => 'date',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** @return HasMany<Service, $this> */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class)->orderBy('position')->orderBy('name');
    }

    /** @return HasMany<Article, $this> */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /** @param Builder<$this> $query */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    /** @param Builder<$this> $query */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('position')->orderBy('name');
    }
}
