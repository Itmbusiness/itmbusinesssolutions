<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'email', 'requested_at', 'confirmed_at', 'revoked_at', 'policy_version',
    'source', 'access_token', 'access_token_hash',
])]
class MarketingSubscription extends Model
{
    protected function casts(): array
    {
        return [
            'requested_at' => 'datetime',
            'confirmed_at' => 'datetime',
            'revoked_at' => 'datetime',
            'access_token' => 'encrypted',
        ];
    }

    /** @return HasMany<MarketingConsentEvent, $this> */
    public function events(): HasMany
    {
        return $this->hasMany(MarketingConsentEvent::class);
    }

    public function isActive(): bool
    {
        return $this->confirmed_at !== null && $this->revoked_at === null;
    }

    /** @param Builder<$this> $query */
    public function scopeActive(Builder $query): void
    {
        $query->whereNotNull('confirmed_at')->whereNull('revoked_at');
    }
}
