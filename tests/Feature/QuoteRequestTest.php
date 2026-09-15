<?php

namespace Tests\Feature;

use App\Enums\LeadStatus;
use App\Models\Lead;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuoteRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    private function service(): Service
    {
        $category = ServiceCategory::create(['slug' => 'tenders', 'name' => 'Tenders', 'is_published' => true]);

        return Service::create([
            'service_category_id' => $category->id,
            'slug' => 'praz-registration',
            'name' => 'PRAZ Registration Support',
            'is_published' => true,
        ]);
    }

    /** @return array<string, mixed> */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Tendai Moyo',
            'email' => 'tendai@example.co.zw',
            'phone' => '+263 77 123 4567',
            'organisation_name' => 'Sunrise Logistics',
            'organisation_status' => 'registered',
            'goal' => 'tenders',
            'message' => 'We want to bid on a council tender.',
            'preferred_channel' => 'whatsapp',
            'urgency' => 'urgent',
            'consent' => '1',
        ], $overrides);
    }

    public function test_a_guest_can_open_the_request_form(): void
    {
        $this->get(route('quote.create'))->assertOk()->assertSee('Request a written quotation');
    }

    public function test_the_form_preselects_a_service_from_the_query_string(): void
    {
        $service = $this->service();

        $this->get(route('quote.create', ['service' => $service->slug]))
            ->assertOk()
            ->assertSee('PRAZ Registration Support');
    }

    public function test_a_guest_can_submit_a_request_without_an_account(): void
    {
        $service = $this->service();

        $response = $this->post(route('quote.store'), $this->validPayload(['service_id' => $service->id]));

        $response->assertRedirect(route('quote.received'))->assertSessionHas('lead_reference');

        $lead = Lead::sole();
        $this->assertSame('Tendai Moyo', $lead->name);
        $this->assertSame($service->id, $lead->service_id);
        $this->assertNull($lead->user_id);
        $this->assertSame(LeadStatus::New, $lead->status);
        $this->assertNotNull($lead->consent_at);
    }

    public function test_the_reference_is_shown_once_on_the_confirmation_page(): void
    {
        $this->post(route('quote.store'), $this->validPayload());

        $this->get(route('quote.received'))
            ->assertOk()
            ->assertSee(Lead::sole()->reference);
    }

    public function test_a_request_without_consent_is_rejected(): void
    {
        $this->post(route('quote.store'), $this->validPayload(['consent' => null]))
            ->assertSessionHasErrors('consent');

        $this->assertSame(0, Lead::count());
    }

    public function test_a_request_without_contact_details_is_rejected(): void
    {
        $this->post(route('quote.store'), $this->validPayload(['email' => 'not-an-email']))
            ->assertSessionHasErrors('email');

        $this->assertSame(0, Lead::count());
    }

    /** The hidden field is bait; a filled one means a bot. */
    public function test_a_submission_that_fills_the_honeypot_is_rejected(): void
    {
        $this->post(route('quote.store'), $this->validPayload(['website' => 'http://spam.example']))
            ->assertSessionHasErrors('website');

        $this->assertSame(0, Lead::count());
    }

    public function test_references_are_unique_and_readable(): void
    {
        $this->post(route('quote.store'), $this->validPayload());
        $this->post(route('quote.store'), $this->validPayload(['email' => 'second@example.co.zw']));

        $references = Lead::pluck('reference');

        $this->assertCount(2, $references->unique());

        foreach ($references as $reference) {
            $this->assertMatchesRegularExpression('/^LEAD-[A-HJ-NP-Z2-9]{6}$/', $reference);
        }
    }

    public function test_tracking_requires_both_the_reference_and_the_matching_email(): void
    {
        $this->post(route('quote.store'), $this->validPayload());
        $lead = Lead::sole();

        $this->post(route('track.lookup'), [
            'reference' => $lead->reference,
            'email' => 'tendai@example.co.zw',
        ])->assertOk()->assertSee($lead->reference);

        $this->post(route('track.lookup'), [
            'reference' => $lead->reference,
            'email' => 'attacker@example.com',
        ])->assertOk()->assertSee('No matching request found');
    }

    /** Guest tracking must never leak internal notes (FR-09). */
    public function test_tracking_never_exposes_internal_notes(): void
    {
        $this->post(route('quote.store'), $this->validPayload());
        $lead = Lead::sole();
        $lead->update(['internal_notes' => 'SENSITIVE INTERNAL DETAIL']);

        $this->post(route('track.lookup'), [
            'reference' => $lead->reference,
            'email' => $lead->email,
        ])->assertOk()->assertDontSee('SENSITIVE INTERNAL DETAIL');
    }
}
