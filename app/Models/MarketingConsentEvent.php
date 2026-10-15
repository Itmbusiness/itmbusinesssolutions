<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'marketing_subscription_id', 'lead_id', 'action', 'source',
    'policy_version', 'occurred_at',
])]
class MarketingConsentEvent extends Model
{
    public $timestamps = false;

    protected function casts(): array
    {
        return ['occurred_at' => 'datetime'];
    }

    /** @return BelongsTo<MarketingSubscription, $this> */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(MarketingSubscription::class, 'marketing_subscription_id');
    }

    /** @return BelongsTo<Lead, $this> */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }
}
