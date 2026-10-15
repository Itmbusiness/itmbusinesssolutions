<?php

namespace Tests\Feature;

use App\Enums\LeadStatus;
use App\Enums\UserRole;
use App\Models\Article;
use App\Models\Lead;
use App\Models\Page;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => UserRole::Admin]);
    }

    private function category(): ServiceCategory
    {
        return ServiceCategory::create(['slug' => 'company-registration', 'name' => 'Company Registration', 'is_published' => true]);
    }

    public function test_guests_are_redirected_from_the_admin(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_a_client_cannot_reach_the_admin(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_a_consultant_cannot_reach_the_admin(): void
    {
        $this->actingAs(User::factory()->consultant()->create())
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_an_admin_sees_the_dashboard(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Overview');
    }

    public function test_an_admin_can_create_a_service_that_appears_on_the_public_site(): void
    {
        $category = $this->category();

        $this->actingAs($this->admin())->post(route('admin.services.store'), [
            'service_category_id' => $category->id,
            'name' => 'Shelf Company Transfer',
            'summary' => 'Take over a ready-made company.',
            'intro' => 'A faster route to a registered entity.',
            'requirements' => "Director details\nProof of address",
            'steps' => "Tell us your goal\nWe prepare the transfer",
            'is_published' => '1',
        ])->assertRedirect();

        $service = Service::where('slug', 'shelf-company-transfer')->sole();
        $this->assertSame(['Director details', 'Proof of address'], $service->requirements);

        $this->get(route('services.show', $service))
            ->assertOk()
            ->assertSee('Shelf Company Transfer')
            ->assertSee('Proof of address');
    }

    public function test_an_admin_can_edit_a_service_and_the_public_page_changes(): void
    {
        $category = $this->category();
        $service = Service::create([
            'service_category_id' => $category->id,
            'slug' => 'praz-registration',
            'name' => 'PRAZ Registration',
            'summary' => 'Original summary.',
            'is_published' => true,
        ]);

        $this->actingAs($this->admin())->put(route('admin.services.update', $service), [
            'service_category_id' => $category->id,
            'name' => 'PRAZ Registration',
            'slug' => 'praz-registration',
            'summary' => 'Updated summary for tenders.',
            'requirements' => 'Tax clearance certificate',
            'is_published' => '1',
        ])->assertRedirect();

        $this->get(route('services.show', $service))
            ->assertSee('Tax clearance certificate')
            ->assertDontSee('Original summary.');
    }

    public function test_unpublishing_a_service_removes_it_from_the_public_site(): void
    {
        $category = $this->category();
        $service = Service::create([
            'service_category_id' => $category->id,
            'slug' => 'vat-registration',
            'name' => 'VAT Registration',
            'is_published' => true,
        ]);

        // No is_published key means the checkbox was unticked.
        $this->actingAs($this->admin())->put(route('admin.services.update', $service), [
            'service_category_id' => $category->id,
            'name' => 'VAT Registration',
            'slug' => 'vat-registration',
        ])->assertRedirect();

        $this->get(route('services.show', $service))->assertNotFound();
    }

    public function test_an_admin_can_publish_an_article(): void
    {
        $this->actingAs($this->admin())->post(route('admin.articles.store'), [
            'title' => 'Why a tax clearance may be delayed',
            'excerpt' => 'The common causes.',
            'body' => '## Causes',
            'is_published' => '1',
        ])->assertRedirect();

        $article = Article::sole();
        $this->assertTrue($article->is_published);

        $this->get(route('articles.show', $article))->assertOk()->assertSee('Why a tax clearance may be delayed');
    }

    public function test_an_admin_can_edit_page_copy(): void
    {
        $page = Page::create(['slug' => 'about', 'title' => 'About', 'body' => 'Old copy.', 'is_published' => true, 'is_system' => true]);

        $this->actingAs($this->admin())->put(route('admin.pages.update', $page), [
            'title' => 'About ITM',
            'body' => 'Brand new copy about the firm.',
            'is_published' => '1',
            'is_indexable' => '1',
        ])->assertRedirect();

        $this->get(route('page', 'about'))->assertSee('Brand new copy about the firm.');
    }

    public function test_an_admin_can_change_contact_settings_shown_across_the_site(): void
    {
        $this->actingAs($this->admin())->put(route('admin.settings.update'), [
            'settings' => ['contact.phone' => '+263 24 123 4567'],
        ])->assertRedirect();

        $this->assertSame('+263 24 123 4567', Setting::get('contact.phone'));

        $this->get(route('home'))->assertSee('+263 24 123 4567');
    }

    public function test_an_admin_can_move_a_lead_through_its_status_model(): void
    {
        $lead = Lead::factory()->create();

        $this->actingAs($this->admin())->patch(route('admin.leads.update', $lead), [
            'status' => LeadStatus::Qualified->value,
            'internal_notes' => 'Called, sounds genuine.',
        ])->assertRedirect();

        $lead->refresh();
        $this->assertSame(LeadStatus::Qualified, $lead->status);
        $this->assertSame('Called, sounds genuine.', $lead->internal_notes);
    }

    public function test_itm_leads_can_only_be_assigned_to_administrators(): void
    {
        $lead = Lead::factory()->create();
        $client = User::factory()->create();
        $consultant = User::factory()->consultant()->create();
        $admin = $this->admin();

        $this->actingAs($admin)->patch(route('admin.leads.update', $lead), [
            'status' => LeadStatus::New->value,
            'assigned_to' => $client->id,
        ])->assertSessionHasErrors('assigned_to');

        $this->assertNull($lead->refresh()->assigned_to);

        $this->actingAs($admin)->patch(route('admin.leads.update', $lead), [
            'status' => LeadStatus::New->value,
            'assigned_to' => $consultant->id,
        ])->assertSessionHasErrors('assigned_to');

        $this->assertNull($lead->refresh()->assigned_to);

        $this->actingAs($admin)->patch(route('admin.leads.update', $lead), [
            'status' => LeadStatus::New->value,
            'assigned_to' => $admin->id,
        ])->assertRedirect();

        $this->assertSame($admin->id, $lead->refresh()->assigned_to);
        $this->assertNotNull($lead->assigned_at);

        $this->actingAs($admin)->get(route('admin.leads.show', $lead))
            ->assertOk()->assertDontSee($consultant->name);
    }

    public function test_an_admin_can_record_the_first_human_response_once(): void
    {
        $lead = Lead::factory()->create();
        $admin = $this->admin();

        $this->actingAs($admin)->get(route('admin.leads.index', ['response' => 'waiting']))
            ->assertOk()->assertSee($lead->reference);

        $this->actingAs($admin)->post(route('admin.leads.response', $lead))->assertRedirect();

        $firstResponse = $lead->refresh()->first_responded_at;
        $this->assertNotNull($firstResponse);
        $this->assertSame($admin->id, $lead->response_recorded_by);

        $this->actingAs($admin)->post(route('admin.leads.response', $lead))->assertRedirect();

        $this->assertTrue($firstResponse->equalTo($lead->refresh()->first_responded_at));
        $this->actingAs($admin)->get(route('admin.leads.index', ['response' => 'waiting']))
            ->assertOk()->assertDontSee($lead->reference);
    }

    public function test_a_legacy_consultant_assignment_is_flagged_for_review(): void
    {
        $consultant = User::factory()->consultant()->create();
        $lead = Lead::factory()->create(['assigned_to' => $consultant->id]);

        $this->actingAs($this->admin())->get(route('admin.leads.show', $lead))
            ->assertOk()->assertSee('Review this assignment');
    }

    public function test_a_client_cannot_record_a_human_response(): void
    {
        $lead = Lead::factory()->create();

        $this->actingAs(User::factory()->create())
            ->post(route('admin.leads.response', $lead))->assertForbidden();

        $this->assertNull($lead->refresh()->first_responded_at);
    }

    public function test_an_admin_can_grant_and_revoke_administrator_access(): void
    {
        $admin = $this->admin();
        $user = User::factory()->create();

        $this->actingAs($admin)->patch(route('admin.users.update', $user), [
            'role' => UserRole::Admin->value,
        ])->assertRedirect();

        $this->assertTrue($user->refresh()->isAdmin());
    }

    /** Locking every administrator out of the admin must not be possible. */
    public function test_the_last_administrator_cannot_be_demoted(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->patch(route('admin.users.update', $admin), [
            'role' => UserRole::Client->value,
        ])->assertSessionHasErrors('role');

        $this->assertTrue($admin->refresh()->isAdmin());
    }

    public function test_a_category_with_services_cannot_be_deleted(): void
    {
        $category = $this->category();
        Service::create([
            'service_category_id' => $category->id,
            'slug' => 'pbc-registration',
            'name' => 'PBC Registration',
            'is_published' => true,
        ]);

        $this->actingAs($this->admin())
            ->delete(route('admin.categories.destroy', $category))
            ->assertSessionHasErrors('category');

        $this->assertDatabaseHas('service_categories', ['id' => $category->id]);
    }
}
