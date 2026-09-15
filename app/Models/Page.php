<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string|null $seo_title
 * @property string|null $meta_description
 * @property string|null $body
 * @property bool $is_published
 * @property bool $is_indexable
 * @property bool $is_system
 */
#[Fillable([
    'slug', 'title', 'seo_title', 'meta_description', 'body',
    'is_published', 'is_indexable', 'is_system',
])]
class Page extends Model
{
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'is_indexable' => 'boolean',
            'is_system' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** @return MorphMany<Faq, $this> */
    public function faqs(): MorphMany
    {
        return $this->morphMany(Faq::class, 'faqable')->orderBy('position');
    }

    /** @param Builder<$this> $query */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }
}
