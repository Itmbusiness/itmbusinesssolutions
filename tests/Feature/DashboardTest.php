<?php

namespace Tests\Feature;

use App\Enums\LeadStatus;
use App\Models\ConsultantCustomer;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_visit_the_dashboard(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response->assertOk();
    }

    public function test_a_client_sees_the_client_workspace(): void
    {
        $this->withoutVite()->actingAs(User::factory()->create());

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Open requests')
            ->assertSee('Request a Service')
            ->assertDontSee('My customers');
    }

    public function test_a_consultant_sees_their_own_customer_book_instead(): void
    {
        $this->withoutVite()->actingAs(User::factory()->consultant()->create());

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('My business')
            ->assertSee('No customers yet')
            ->assertDontSee('Awaiting information');
    }

    public function test_a_client_can_review_all_their_requests_without_seeing_another_clients(): void
    {
        $client = User::factory()->create();
        $other = User::factory()->create();
        $mine = Lead::factory()->for($client)->status(LeadStatus::AwaitingInformation)->create();
        $theirs = Lead::factory()->for($other)->create();

        $this->withoutVite()->actingAs($client);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Awaiting information')
            ->assertSee('View all requests')
            ->assertSee($mine->reference)
            ->assertDontSee($theirs->reference);

        $this->get(route('service-requests.index'))
            ->assertOk()
            ->assertSee($mine->reference)
            ->assertDontSee($theirs->reference);
    }

    public function test_a_consultant_dashboard_shows_their_overdue_tasks_only(): void
    {
        $consultant = User::factory()->consultant()->create();
        $other = User::factory()->consultant()->create();
        $customer = ConsultantCustomer::factory()->for($consultant, 'consultant')->create();
        $otherCustomer = ConsultantCustomer::factory()->for($other, 'consultant')->create();
        $customer->tasks()->create(['consultant_id' => $consultant->id, 'title' => 'Call today', 'due_on' => today()->subDay()]);
        $otherCustomer->tasks()->create(['consultant_id' => $other->id, 'title' => 'Other task', 'due_on' => today()->subDay()]);

        $this->withoutVite()->actingAs($consultant)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Overdue follow-ups')
            ->assertSee('Call today')
            ->assertSee('Overdue')
            ->assertDontSee('Other task');
    }

    /**
     * The design-system board is a builder's reference, not a product surface —
     * it must never appear in the signed-in navigation.
     */
    public function test_the_workspace_does_not_link_to_the_design_system(): void
    {
        $this->withoutVite()->actingAs(User::factory()->create());

        $this->get(route('dashboard'))->assertDontSee('Design system');
    }

    public function test_the_workspace_offers_a_way_back_to_the_public_site(): void
    {
        $this->withoutVite()->actingAs(User::factory()->create());

        $this->get(route('dashboard'))
            ->assertSee('Back to website')
            ->assertSee(route('home'));
    }
}
