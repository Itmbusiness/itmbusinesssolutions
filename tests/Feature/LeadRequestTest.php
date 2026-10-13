<?php

namespace Tests\Feature;

use App\Mail\LeadConfirmation;
use App\Mail\NewLeadReceived;
use App\Models\Lead;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * The signed-in "Request another service" flow (PRD Journey B).
 */
class LeadRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_guests_cannot_open_the_request_form(): void
    {
        $this->get(route('service-requests.create'))->assertRedirect(route('login'));
    }

    public function test_an_authenticated_user_can_open_the_request_form(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('service-requests.create'))
            ->assertOk()
            ->assertSee('Request a Service');
    }

    public function test_a_request_is_stored_against_the_signed_in_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('service-requests.store'), [
            'goal' => 'tenders',
            'message' => 'We want to bid on a council tender.',
            'preferred_channel' => 'whatsapp',
            'consent' => '1',
        ])->assertRedirect(route('dashboard'))->assertSessionHas('status');

        $lead = Lead::sole();
        $this->assertSame($user->id, $lead->user_id);
        $this->assertSame($user->email, $lead->email);
        $this->assertSame('workspace', $lead->source);
    }

    public function test_a_request_confirms_to_the_client(): void
    {
        Mail::fake();

        Setting::put('contact.email', 'hello@itmbusinesssolutions.co.zw', 'contact');

        $user = User::factory()->create();

        $this->actingAs($user)->post(route('service-requests.store'), [
            'goal' => 'tenders',
            'preferred_channel' => 'whatsapp',
            'consent' => '1',
        ]);

        $lead = Lead::sole();

        Mail::assertQueued(LeadConfirmation::class, fn ($mail) => $mail->hasTo($user->email)
            && $mail->lead->is($lead));
        Mail::assertQueued(NewLeadReceived::class);
    }

    public function test_a_request_without_consent_is_rejected(): void
    {
        $this->actingAs(User::factory()->create())->post(route('service-requests.store'), [
            'goal' => 'tenders',
            'preferred_channel' => 'whatsapp',
        ])->assertSessionHasErrors('consent');

        $this->assertSame(0, Lead::count());
    }

    public function test_an_unknown_goal_is_rejected(): void
    {
        $this->actingAs(User::factory()->create())->post(route('service-requests.store'), [
            'goal' => 'something-invented',
            'preferred_channel' => 'whatsapp',
            'consent' => '1',
        ])->assertSessionHasErrors('goal');

        $this->assertSame(0, Lead::count());
    }

    public function test_a_request_cannot_be_filed_against_another_user(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($user)->post(route('service-requests.store'), [
            'user_id' => $other->id,
            'goal' => 'tenders',
            'preferred_channel' => 'whatsapp',
            'consent' => '1',
        ]);

        $this->assertSame($user->id, Lead::sole()->user_id);
    }
}
