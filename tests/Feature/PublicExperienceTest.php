<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Page;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicExperienceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_requirements_lists_published_checklists_and_their_database_content(): void
    {
        Page::create(['slug' => 'requirements', 'title' => 'Requirements Centre', 'body' => 'Prepare for assessment.', 'is_published' => true]);
        $category = ServiceCategory::create(['slug' => 'registration', 'name' => 'Registration', 'is_published' => true]);
        Service::create(['service_category_id' => $category->id, 'slug' => 'published', 'name' => 'Published Service', 'requirements' => ['Director details'], 'is_published' => true]);
        Service::create(['service_category_id' => $category->id, 'slug' => 'draft', 'name' => 'Draft Service', 'requirements' => ['Private checklist'], 'is_published' => false]);
        $hiddenCategory = ServiceCategory::create(['slug' => 'hidden-category', 'name' => 'Hidden Category', 'is_published' => false]);
        Service::create(['service_category_id' => $hiddenCategory->id, 'slug' => 'hidden-category-service', 'name' => 'Hidden Category Service', 'is_published' => true]);

        $this->get(route('page', 'requirements'))->assertOk()
            ->assertSee('Published Service')->assertSee('Director details')
            ->assertSee('checklist-search')->assertDontSee('Draft Service')
            ->assertDontSee('Private checklist')->assertDontSee('Hidden Category Service');
    }

    public function test_the_generated_home_image_can_be_overridden_through_settings(): void
    {
        $this->get(route('home'))->assertOk()->assertSee('images/public/registration-v1.webp');
        Setting::put('media.registration', 'images/custom-home.webp', 'public images');
        $this->get(route('home'))->assertOk()->assertSee('images/custom-home.webp');
    }

    public function test_whatsapp_uses_the_configured_number(): void
    {
        Setting::put('contact.whatsapp', '+263 775 423 197', 'contact');
        $this->get(route('home'))->assertOk()->assertSee('https://wa.me/263775423197');
    }

    public function test_admin_image_changes_reach_the_public_site(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $this->actingAs($admin)->put(route('admin.settings.update'), [
            'settings' => ['media.registration' => 'images/custom-home.webp'],
        ])->assertRedirect()->assertSessionHasNoErrors();
        $this->get(route('home'))->assertOk()->assertSee('images/custom-home.webp');
    }

    public function test_admin_image_paths_cannot_escape_the_images_directory(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $this->actingAs($admin)->put(route('admin.settings.update'), [
            'settings' => ['media.registration' => '../private-file.webp'],
        ])->assertSessionHasErrors('settings.media.registration');
        $this->assertNull(Setting::get('media.registration'));
    }

    public function test_the_quote_page_preserves_the_form_and_explains_the_next_steps(): void
    {
        $this->get(route('quote.create'))->assertOk()
            ->assertSee('What happens next')->assertSee('You approve before payment')
            ->assertSee('name="consent"', false)->assertSee('name="website"', false)
            ->assertSee('images/public/quotation-v1.webp');
    }

    public function test_about_keeps_cms_sections_in_the_illustrated_layout(): void
    {
        Page::create(['slug' => 'about', 'title' => 'About ITM', 'body' => "Our introduction.\n\n## Our approach\n\n- Understand the outcome\n- Explain the requirements", 'is_published' => true]);
        $this->get(route('page', 'about'))->assertOk()->assertSee('Our approach')
            ->assertSee('Understand the outcome')->assertSee('images/public/consultation-v1.webp');
    }
}
