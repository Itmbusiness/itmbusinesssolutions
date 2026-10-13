<?php

namespace App\Models;

use App\Enums\LeadStatus;
use Database\Factories\LeadFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'reference', 'user_id', 'service_id', 'name', 'email', 'phone', 'organisation_name',
    'organisation_status', 'goal', 'message', 'preferred_channel', 'urgency', 'source',
    'status', 'assigned_to', 'internal_notes', 'consent_at',
    'utm_source', 'utm_medium', 'utm_campaign',
])]
class Lead extends Model
{
    /** @use HasFactory<LeadFactory> */
    use HasFactory;

    protected $attributes = [
        'status' => LeadStatus::New->value,
        'source' => 'website',
    ];

    protected function casts(): array
    {
        return [
            'status' => LeadStatus::class,
            'consent_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $lead): void {
            $lead->reference ??= self::generateReference();
        });
    }

    /**
     * A short, human-quotable reference. Ambiguous characters are excluded so
     * it survives being read aloud over the phone or typed from WhatsApp.
     */
    public static function generateReference(): string
    {
        // Characters that survive being read aloud or typed from a photo.
        $alphabet = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';

        do {
            $suffix = '';

            for ($i = 0; $i < 6; $i++) {
                $suffix .= $alphabet[random_int(0, strlen($alphabet) - 1)];
            }

            $reference = 'LEAD-'.$suffix;
        } while (self::where('reference', $reference)->exists());

        return $reference;
    }

    /** @return BelongsTo<Service, $this> */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<User, $this> */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /** @param Builder<$this> $query */
    public function scopeOpen(Builder $query): void
    {
        $query->whereIn('status', array_map(
            fn (LeadStatus $status) => $status->value,
            array_filter(LeadStatus::cases(), fn (LeadStatus $status) => $status->isOpen()),
        ));
    }
}
