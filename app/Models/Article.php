<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $service_category_id
 * @property string $slug
 * @property string $title
 * @property string|null $excerpt
 * @property string|null $body
 * @property string|null $seo_title
 * @property string|null $meta_description
 * @property string|null $primary_keyword
 * @property string|null $author
 * @property string|null $reviewer
 * @property CarbonInterface|null $reviewed_on
 * @property CarbonInterface|null $next_review_date
 * @property bool $is_published
 * @property CarbonInterface|null $published_at
 * @property int $position
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 */
#[Fillable([
    'service_category_id', 'slug', 'title', 'excerpt', 'body', 'seo_title', 'meta_description',
    'primary_keyword', 'author', 'reviewer', 'reviewed_on', 'next_review_date',
    'is_published', 'published_at', 'position',
])]
class Article extends Model
{
    protected function casts(): array
    {
        return [
            'reviewed_on' => 'date',
            'next_review_date' => 'date',
            'published_at' => 'datetime',
            'is_published' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** @return BelongsTo<ServiceCategory, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    /** @param Builder<$this> $query */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    public function isStale(): bool
    {
        return $this->next_review_date !== null && $this->next_review_date->isPast();
    }
}
