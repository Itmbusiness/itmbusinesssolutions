<?php

namespace Tests\Feature;

use App\Mail\ConfirmMarketingSubscription;
use App\Mail\LeadConfirmation;
use App\Models\Lead;
use App\Models\MarketingConsentEvent;
use App\Models\MarketingSubscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MarketingConsentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        Mail::fake();
    }

    /** @return array<string, string> */
    private function enquiry(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Tendai Moyo',
            'email' => 'Tendai@Example.co.zw',
            'preferred_channel' => 'email',
            'consent' => '1',
        ], $overrides);
    }

    public function test_forms_offer_a_separate_optional_marketing_choice(): void
    {
        $this->get(route('quote.create'))->assertOk()
            ->assertSee('name="marketing_opt_in"', false)
            ->assertSee('Optional. We will email you to confirm this choice');

        $this->actingAs(User::factory()->create())
            ->get(route('service-requests.create'))->assertOk()
            ->assertSee('name="marketing_opt_in"', false);
    }

    public function test_an_enquiry_without_marketing_opt_in_only_sends_transactional_mail(): void
    {
        $this->post(route('quote.store'), $this->enquiry())
            ->assertRedirect(route('quote.received'));

        $this->assertSame(1, Lead::count());
        $this->assertSame(0, MarketingSubscription::count());
        Mail::assertQueued(LeadConfirmation::class);
        Mail::assertNotQueued(ConfirmMarketingSubscription::class);
    }

    public function test_marketing_choice_records_a_pending_request_and_sends_confirmation(): void
    {
        $this->post(route('quote.store'), $this->enquiry(['marketing_opt_in' => '1']))
            ->assertRedirect(route('quote.received'))
            ->assertSessionHas('marketing_confirmation_pending', true);

        $subscription = MarketingSubscription::sole();
        $this->assertSame('tendai@example.co.zw', $subscription->email);
        $this->assertSame('website_enquiry', $subscription->source);
        $this->assertSame(config('marketing.consent_version'), $subscription->policy_version);
        $this->assertNotNull($subscription->requested_at);
        $this->assertNull($subscription->confirmed_at);
        $this->assertFalse($subscription->isActive());
        $this->assertSame(0, MarketingSubscription::active()->count());

        $event = MarketingConsentEvent::sole();
        $this->assertSame(Lead::sole()->id, $event->lead_id);
        $this->assertSame('requested', $event->action);
        Mail::assertQueued(ConfirmMarketingSubscription::class);
        $this->assertStringContainsString('Review and confirm', (new ConfirmMarketingSubscription($subscription))->render());
    }

    public function test_confirmation_and_unsubscribe_require_the_token_and_are_idempotent(): void
    {
        $this->post(route('quote.store'), $this->enquiry(['marketing_opt_in' => '1']));
        $subscription = MarketingSubscription::sole();
        $token = $subscription->access_token;

        $this->get(route('marketing.preferences.show', $token))
            ->assertOk()->assertSee('Confirm email updates');
        $this->assertNull($subscription->refresh()->confirmed_at);

        $this->post(route('marketing.preferences.confirm', $token))->assertRedirect();
        $this->post(route('marketing.preferences.confirm', $token))->assertRedirect();
        $this->assertTrue($subscription->refresh()->isActive());
        $this->assertSame(1, MarketingSubscription::active()->count());
        $this->assertSame(1, MarketingConsentEvent::where('action', 'confirmed')->count());

        $this->post(route('marketing.preferences.unsubscribe', $token))->assertRedirect();
        $this->post(route('marketing.preferences.unsubscribe', $token))->assertRedirect();
        $this->post(route('marketing.preferences.confirm', $token))->assertRedirect();
        $this->assertFalse($subscription->refresh()->isActive());
        $this->assertSame(0, MarketingSubscription::active()->count());
        $this->assertNotNull($subscription->revoked_at);
        $this->assertSame(1, MarketingConsentEvent::where('action', 'revoked')->count());

        $this->get(route('marketing.preferences.show', 'invalid'))->assertNotFound();
        $this->post(route('marketing.preferences.confirm', 'invalid'))->assertNotFound();
    }

    public function test_a_pending_request_can_be_declined_without_becoming_active(): void
    {
        $this->post(route('quote.store'), $this->enquiry(['marketing_opt_in' => '1']));
        $subscription = MarketingSubscription::sole();

        $this->post(route('marketing.preferences.unsubscribe', $subscription->access_token))->assertRedirect();

        $this->assertNull($subscription->refresh()->confirmed_at);
        $this->assertNotNull($subscription->revoked_at);
        $this->assertSame(0, MarketingSubscription::active()->count());
    }

    public function test_a_new_explicit_request_after_unsubscribe_needs_new_confirmation(): void
    {
        $this->post(route('quote.store'), $this->enquiry(['marketing_opt_in' => '1']));
        $subscription = MarketingSubscription::sole();
        $oldToken = $subscription->access_token;
        $this->post(route('marketing.preferences.confirm', $oldToken));
        $this->post(route('marketing.preferences.unsubscribe', $oldToken));

        $this->post(route('quote.store'), $this->enquiry(['marketing_opt_in' => '1']));

        $this->assertSame(1, MarketingSubscription::count());
        $this->assertNull($subscription->refresh()->confirmed_at);
        $this->assertNull($subscription->revoked_at);
        $this->assertNotSame($oldToken, $subscription->access_token);
        $this->get(route('marketing.preferences.show', $oldToken))->assertNotFound();
        $this->assertSame(2, MarketingConsentEvent::where('action', 'requested')->count());
    }

    public function test_signed_in_requests_use_the_same_separate_consent_flow(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('service-requests.store'), [
            'goal' => 'tenders',
            'preferred_channel' => 'email',
            'consent' => '1',
            'marketing_opt_in' => '1',
        ])->assertRedirect(route('dashboard'));

        $this->assertSame('workspace_request', MarketingSubscription::sole()->source);
        $this->assertSame(Lead::sole()->id, MarketingConsentEvent::sole()->lead_id);
        Mail::assertQueued(ConfirmMarketingSubscription::class);
    }

    public function test_invalid_marketing_choice_rejects_the_entire_enquiry(): void
    {
        $this->post(route('quote.store'), $this->enquiry(['marketing_opt_in' => 'yes please']))
            ->assertSessionHasErrors('marketing_opt_in');

        $this->assertSame(0, Lead::count());
        $this->assertSame(0, MarketingSubscription::count());
    }
}
