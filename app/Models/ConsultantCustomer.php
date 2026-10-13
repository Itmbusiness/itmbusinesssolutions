<?php

namespace App\Models;

use App\Enums\ConsultantCustomerStage;
use Database\Factories\ConsultantCustomerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['consultant_id', 'name', 'email', 'phone', 'stage'])]
class ConsultantCustomer extends Model
{
    /** @use HasFactory<ConsultantCustomerFactory> */
    use HasFactory;

    protected $attributes = [
        'stage' => ConsultantCustomerStage::New->value,
    ];

    protected function casts(): array
    {
        return [
            'stage' => ConsultantCustomerStage::class,
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function consultant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'consultant_id');
    }

    /** @return HasMany<ConsultantCustomerNote, $this> */
    public function notes(): HasMany
    {
        return $this->hasMany(ConsultantCustomerNote::class)->latest();
    }

    /** @return HasMany<ConsultantTask, $this> */
    public function tasks(): HasMany
    {
        return $this->hasMany(ConsultantTask::class)->orderBy('due_on');
    }
}
