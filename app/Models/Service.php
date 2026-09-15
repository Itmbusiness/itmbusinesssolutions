<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int $id
 * @property int $service_category_id
 * @property string $slug
 * @property string $name
 * @property string|null $summary
 * @property string|null $intro
 * @property array<int, string>|null $suited_to
 * @property array<int, string>|null $includes
 * @property array<int, string>|null $requirements
 * @property array<int, string>|null $steps
 * @property string|null $charges_note
 * @property string|null $processing_note
 * @property string|null $source_note
 * @property string|null $seo_title
 * @property string|null $meta_description
 * @property string|null $primary_keyword
 * @property bool $is_featured
 * @property bool $is_published
 * @property int $position
 * @property CarbonInterface|null $reviewed_on
 * @property CarbonInterface|null $next_review_date
 * @property string|null $reviewer
 */
#[Fillable([
    'service_category_id', 'slug', 'name', 'summary', 'intro', 'suited_to', 'includes',
    'requirements', 'steps', 'charges_note', 'processing_note', 'source_note', 'seo_title',
    'meta_description', 'primary_keyword', 'is_featured', 'is_published', 'position',
    'reviewed_on', 'next_review_date', 'reviewer',
])]
class Service extends Model
{
    protected function casts(): array
    {
        return [
            'suited_to' => 'array',
            'includes' => 'array',
            'requirements' => 'array',
            'steps' => 'array',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'reviewed_on' => 'date',
            'next_review_date' => 'date',
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

    /** @return MorphMany<Faq, $this> */
    public function faqs(): MorphMany
    {
        return $this->morphMany(Faq::class, 'faqable')->orderBy('position');
    }

    /** @return HasMany<Lead, $this> */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    /** @return BelongsToMany<Service, $this> */
    public function relatedServices(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'related_services', 'service_id', 'related_service_id')
            ->withPivot('position')
            ->orderBy('position');
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

    /**
     * Regulated pages carry a review date; once it passes, the page is stale
     * and the template shows an assessment notice instead of implying currency.
     */
    public function isStale(): bool
    {
        return $this->next_review_date !== null && $this->next_review_date->isPast();
    }
}
