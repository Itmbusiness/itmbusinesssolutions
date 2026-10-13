<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\Setting;
use Database\Seeders\LegalPagesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegalPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_targeted_import_preserves_other_content_settings_and_publication_choices(): void
    {
        Page::create(['slug' => 'about', 'title' => 'Owner title', 'body' => 'Owner copy']);
        Page::create(['slug' => 'privacy', 'title' => 'Old policy', 'is_published' => false, 'is_indexable' => false]);
        Setting::put('contact.address', 'Owner office address');
        $this->seed(LegalPagesSeeder::class);
        $this->seed(LegalPagesSeeder::class);

        $this->assertDatabaseCount('pages', 3);
        $this->assertSame('Owner copy', Page::where('slug', 'about')->sole()->body);
        $this->assertSame('Owner office address', Setting::get('contact.address'));
        $policy = Page::where('slug', 'privacy')->sole();
        $this->assertFalse($policy->is_published);
        $this->assertFalse($policy->is_indexable);
        $this->get('/privacy')->assertNotFound();
    }

    public function test_legal_pages_render_readable_sections_with_working_cross_links(): void
    {
        $this->seed(LegalPagesSeeder::class);
        $privacy = $this->get('/privacy')->assertOk()->assertSee('ITM Business Solutions')
            ->assertSee('href="/terms"', false)->assertSee('href="/contact"', false)
            ->assertDontSee('The final policy must')->assertDontSee('Production legal review is required');
        $terms = $this->get('/terms')->assertOk()->assertSee('Terms and Conditions')
            ->assertSee('href="/privacy"', false)->assertDontSee('The final terms must');

        foreach ([$privacy, $terms] as $response) {
            preg_match_all('/href="#(legal-section-[0-9]+)"/', $response->getContent(), $links);
            $this->assertNotEmpty($links[1]);
            foreach ($links[1] as $id) {
                $response->assertSee('id="'.$id.'"', false);
            }
        }
    }

    public function test_contact_consent_links_to_the_existing_privacy_route(): void
    {
        $this->seed(LegalPagesSeeder::class);
        Page::create(['slug' => 'contact', 'title' => 'Contact', 'is_published' => true]);
        $this->get('/contact')->assertOk()->assertSee('href="'.route('page', 'privacy').'"', false)
            ->assertDontSee('href="'.route('page', 'privacy-policy').'"', false);
        $this->get(route('page', 'privacy'))->assertOk();
    }
}
