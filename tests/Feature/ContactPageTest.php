<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Mail\LeadConfirmation;
use App\Mail\NewLeadReceived;
use App\Models\Lead;
use App\Models\Page;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Page::create([
            'slug' => 'contact', 'title' => 'Contact ITM', 'is_published' => true,
            'body' => "Our editable introduction.\n\n## Contact form\n\n- Full name\n- Message\n\n## Contact channels\n\nEmail: [Insert verified address]\nOffice: [Insert verified physical address and opening hours]\n\n## Additional information\n\nAn owner-edited note.",
        ]);
    }

    public function test_contact_uses_live_settings_instead_of_imported_placeholders(): void
    {
        Setting::put('contact.email', 'office@example.co.zw');
        Setting::put('contact.address', 'Verified office address');
        Setting::put('contact.phone', '+263 775 423 197');
        Setting::put('contact.whatsapp', '+263 775 423 198');
        Setting::put('contact.hours', 'Monday to Friday, 08:00-17:00');

        $this->get(route('page', 'contact'))->assertOk()
            ->assertSee('Our editable introduction.')->assertSee('An owner-edited note.')
            ->assertSee('office@example.co.zw')->assertSee('Verified office address')
            ->assertSee('Monday to Friday, 08:00-17:00')
            ->assertSee('mailto:office@example.co.zw')->assertSee('tel:+263775423197')
            ->assertSee('https://wa.me/263775423198')->assertSee('Get directions')
            ->assertDontSee('[Insert verified')->assertSee('name="message"', false)
            ->assertSee('action="'.route('quote.store').'"', false);
    }

    public function test_dashboard_contact_changes_update_contact_and_footer_without_reseeding(): void
    {
        Setting::put('contact.address', 'Old office address');
        $this->get(route('page', 'contact'))->assertSee('Old office address');
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $this->actingAs($admin)->put(route('admin.settings.update'), [
            'settings' => ['contact.address' => 'New office address', 'contact.email' => 'updated@example.co.zw'],
        ])->assertSessionHasNoErrors();

        $response = $this->get(route('page', 'contact'))->assertOk()
            ->assertSee('New office address')->assertSee('updated@example.co.zw')
            ->assertDontSee('Old office address')->assertDontSee('[Insert verified');
        $visibleHtml = preg_replace('/<script\b[^>]*>.*?<\/script>/s', '', $response->getContent());
        $this->assertSame(2, substr_count($visibleHtml, 'New office address'));
    }

    public function test_missing_settings_do_not_render_placeholder_contact_links(): void
    {
        $this->get(route('page', 'contact'))->assertOk()->assertDontSee('[Insert verified')
            ->assertDontSee('href="mailto:', false)->assertDontSee('href="tel:', false)
            ->assertDontSee('https://wa.me/')->assertDontSee('Get directions');
    }

    public function test_the_contact_form_lists_only_published_services_in_published_categories(): void
    {
        $category = ServiceCategory::create(['slug' => 'registration', 'name' => 'Registration', 'is_published' => true]);
        Service::create(['service_category_id' => $category->id, 'slug' => 'public-service', 'name' => 'Public Service', 'is_published' => true]);
        Service::create(['service_category_id' => $category->id, 'slug' => 'draft-service', 'name' => 'Draft Service', 'is_published' => false]);
        $hidden = ServiceCategory::create(['slug' => 'hidden', 'name' => 'Hidden category', 'is_published' => false]);
        Service::create(['service_category_id' => $hidden->id, 'slug' => 'hidden-service', 'name' => 'Hidden Service', 'is_published' => true]);
        $this->get(route('page', 'contact'))->assertSee('Public Service')
            ->assertDontSee('Draft Service')->assertDontSee('Hidden Service');
    }

    public function test_the_contact_form_captures_a_lead_and_queues_notifications(): void
    {
        Mail::fake();
        Setting::put('contact.email', 'office@example.co.zw');
        $this->from(route('page', 'contact'))->post(route('quote.store'), [
            'name' => 'Tendai Moyo', 'email' => 'tendai@example.co.zw',
            'message' => 'Please help with our registration.', 'preferred_channel' => 'email', 'consent' => '1',
        ])->assertRedirect(route('quote.received'))->assertSessionHas('lead_reference');
        $this->assertSame('Please help with our registration.', Lead::sole()->message);
        Mail::assertQueued(NewLeadReceived::class);
        Mail::assertQueued(LeadConfirmation::class);
    }

    public function test_contact_validation_returns_to_the_contact_form_with_old_input(): void
    {
        $response = $this->from(route('page', 'contact'))->followingRedirects()->post(route('quote.store'), [
            'name' => 'Tendai Moyo', 'email' => 'not-an-email', 'preferred_channel' => 'email',
        ]);
        $response->assertOk()->assertSee('Please check the form')
            ->assertSee('value="Tendai Moyo"', false)->assertSee('value="not-an-email"', false);
        $this->assertDatabaseCount('leads', 0);
    }
}
