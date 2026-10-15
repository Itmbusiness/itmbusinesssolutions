<?php

namespace App\Support;

use App\Models\Lead;
use App\Models\MarketingSubscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MarketingConsent
{
    /**
     * The form checkbox records a request. Email permission becomes active
     * only after the recipient confirms it through the link we send.
     */
    public function requestForLead(Lead $lead, string $source): ?MarketingSubscription
    {
        $email = mb_strtolower(trim($lead->email));
        $version = config('marketing.consent_version');
        $subscription = MarketingSubscription::where('email', $email)->lockForUpdate()->first()
            ?? new MarketingSubscription(['email' => $email]);

        if ($subscription->isActive() && $subscription->policy_version === $version) {
            $subscription->events()->create([
                'lead_id' => $lead->id,
                'action' => 'requested',
                'source' => $source,
                'policy_version' => $version,
                'occurred_at' => now(),
            ]);

            return null;
        }

        $token = Str::random(48);
        $subscription->fill([
            'requested_at' => now(),
            'confirmed_at' => null,
            'revoked_at' => null,
            'policy_version' => $version,
            'source' => $source,
            'access_token' => $token,
            'access_token_hash' => hash('sha256', $token),
        ])->save();

        $subscription->events()->create([
            'lead_id' => $lead->id,
            'action' => 'requested',
            'source' => $source,
            'policy_version' => $version,
            'occurred_at' => now(),
        ]);

        return $subscription;
    }

    public function findByToken(string $token): MarketingSubscription
    {
        return MarketingSubscription::where('access_token_hash', hash('sha256', $token))->firstOrFail();
    }

    public function confirm(string $token): void
    {
        DB::transaction(function () use ($token): void {
            $subscription = MarketingSubscription::where('access_token_hash', hash('sha256', $token))
                ->lockForUpdate()->firstOrFail();

            if ($subscription->confirmed_at !== null || $subscription->revoked_at !== null) {
                return;
            }

            $subscription->update(['confirmed_at' => now()]);
            $subscription->events()->create([
                'action' => 'confirmed',
                'source' => 'email_link',
                'policy_version' => $subscription->policy_version,
                'occurred_at' => now(),
            ]);
        });
    }

    public function unsubscribe(string $token): void
    {
        DB::transaction(function () use ($token): void {
            $subscription = MarketingSubscription::where('access_token_hash', hash('sha256', $token))
                ->lockForUpdate()->firstOrFail();

            if ($subscription->revoked_at !== null) {
                return;
            }

            $subscription->update(['revoked_at' => now()]);
            $subscription->events()->create([
                'action' => 'revoked',
                'source' => 'email_link',
                'policy_version' => $subscription->policy_version,
                'occurred_at' => now(),
            ]);
        });
    }
}
