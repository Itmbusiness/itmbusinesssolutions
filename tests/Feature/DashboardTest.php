<?php

namespace Tests\Feature;

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
            ->assertSee('No quotations yet')
            ->assertSee('Request a Service')
            ->assertDontSee('No leads yet');
    }

    public function test_a_consultant_sees_the_back_office_instead(): void
    {
        $this->withoutVite()->actingAs(User::factory()->consultant()->create());

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Back office')
            ->assertSee('No leads yet')
            ->assertDontSee('No quotations yet');
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
