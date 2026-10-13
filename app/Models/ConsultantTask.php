<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['consultant_id', 'consultant_customer_id', 'title', 'due_on', 'completed_at'])]
class ConsultantTask extends Model
{
    protected function casts(): array
    {
        return [
            'due_on' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function consultant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'consultant_id');
    }

    /** @return BelongsTo<ConsultantCustomer, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(ConsultantCustomer::class, 'consultant_customer_id');
    }

    /** @param Builder<$this> $query */
    public function scopeOpen(Builder $query): void
    {
        $query->whereNull('completed_at');
    }
}
