<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property string|null $faqable_type
 * @property int|null $faqable_id
 * @property string $question
 * @property string $answer
 * @property string|null $group
 * @property int $position
 * @property bool $is_published
 */
#[Fillable(['faqable_type', 'faqable_id', 'question', 'answer', 'group', 'position', 'is_published'])]
class Faq extends Model
{
    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    /** @return MorphTo<Model, $this> */
    public function faqable(): MorphTo
    {
        return $this->morphTo();
    }

    /** @param Builder<$this> $query */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    /**
     * Site-wide FAQs for the /faq page — the ones not attached to a service.
     *
     * @param  Builder<$this>  $query
     */
    public function scopeGlobal(Builder $query): void
    {
        $query->whereNull('faqable_type');
    }
}
