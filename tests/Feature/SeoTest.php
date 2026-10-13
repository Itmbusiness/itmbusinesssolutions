<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Page;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Setting;
use Database\Seeders\SeoContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function service(bool $categoryPublished = true): Service
    {
        $category = ServiceCategory::create(['slug' => 'company-registration', 'name' => 'Company Registration', 'is_published' => $categoryPublished]);

        return Service::create([
            'service_category_id' => $category->id, 'slug' => 'registration-service', 'name' => 'Registration Service',
            'summary' => 'Registration support in Zimbabwe.', 'is_published' => true, 'is_featured' => true,
        ]);
    }

    public function test_home_metadata_is_editable_and_does_not_duplicate_the_brand(): void
    {
        Page::create(['slug' => 'home', 'title' => 'An owner-managed heading', 'seo_title' => 'Registration | '.config('app.name'), 'meta_description' => 'Owner description', 'is_published' => true]);
        $response = $this->get('/')->assertOk()->assertSee('content="Owner description"', false);
        $visibleText = preg_replace('/\s+/', ' ', strip_tags($response->getContent()));
        $this->assertStringContainsString('An owner-managed heading', $visibleText);
        preg_match('/<title>\s*(.*?)\s*<\/title>/s', $response->getContent(), $match);
        $this->assertSame('Registration | '.config('app.name'), $match[1]);
    }

    public function test_hidden_parent_category_hides_its_service_everywhere(): void
    {
        $service = $this->service(false);
        $this->get(route('services.show', $service))->assertNotFound();
        $this->get('/')->assertDontSee('Registration Service');
        $this->get('/sitemap.xml')->assertDontSee('registration-service');
        $this->get('/request-a-quotation')->assertDontSee('Registration Service');
    }

    public function test_production_metadata_schema_and_sitemap_use_one_public_origin(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        config(['seo.public_url' => 'https://itmbusinesssolutions.co.zw']);
        $service = $this->service();
        $response = $this->get(route('services.show', $service).'?campaign=test')->assertOk();
        $response->assertSee('rel="canonical" href="https://itmbusinesssolutions.co.zw/services/registration-service"', false)
            ->assertDontSee('name="robots" content="noindex', false);
        preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/s', $response->getContent(), $match);
        $schema = json_decode($match[1], true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame('https://itmbusinesssolutions.co.zw/', $schema['@graph'][0]['url']);
        $this->assertSame('https://itmbusinesssolutions.co.zw/services/registration-service', $schema['@graph'][1]['url']);
        $xml = simplexml_load_string($this->get('/sitemap.xml')->getContent());
        $this->assertNotFalse($xml);
        foreach ($xml->url as $entry) {
            $this->assertStringStartsWith('https://itmbusinesssolutions.co.zw/', (string) $entry->loc);
        }
    }

    public function test_sitemap_is_unique_and_honours_hub_noindex_settings(): void
    {
        Page::create(['slug' => 'home', 'title' => 'Home', 'is_published' => true, 'is_indexable' => false]);
        Page::create(['slug' => 'articles', 'title' => 'Reserved route', 'is_published' => true, 'is_indexable' => true]);
        Page::create(['slug' => 'draft', 'title' => 'Draft', 'is_published' => false, 'is_indexable' => true]);
        $xml = simplexml_load_string($this->get('/sitemap.xml')->getContent());
        $this->assertNotFalse($xml);
        $urls = [];
        foreach ($xml->url as $entry) {
            $urls[] = (string) $entry->loc;
        }
        $this->assertNotContains(route('home'), $urls);
        $this->assertNotContains(route('page', 'draft'), $urls);
        $this->assertSame(count($urls), count(array_unique($urls)));
    }

    public function test_organisation_schema_uses_current_settings_and_safely_encodes_text(): void
    {
        Setting::put('contact.email', 'office@example.co.zw');
        Setting::put('contact.address', '</script><script>alert(1)</script>');
        $response = $this->get('/')->assertOk();
        preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/s', $response->getContent(), $match);
        $schema = json_decode($match[1], true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame('office@example.co.zw', $schema['@graph'][0]['email']);
        $this->assertSame('</script><script>alert(1)</script>', $schema['@graph'][0]['address']);
        $this->assertStringNotContainsString('</script>', $match[1]);
        Setting::put('contact.email', 'changed@example.co.zw');
        $this->get('/')->assertSee('changed@example.co.zw');
    }

    public function test_nonproduction_public_pages_are_noindex(): void
    {
        $this->get('/')->assertSee('name="robots" content="noindex, follow"', false);
    }

    public function test_seo_import_is_targeted_and_preserves_owner_content_and_publication_flags(): void
    {
        Page::create(['slug' => 'home', 'title' => 'Old heading', 'body' => 'Owner copy', 'is_published' => true, 'is_indexable' => false]);
        Article::create(['slug' => 'owner-article', 'title' => 'Owner article', 'body' => 'Owner text']);
        $this->seed(SeoContentSeeder::class);
        $this->seed(SeoContentSeeder::class);
        $this->assertDatabaseCount('articles', 2);
        $home = Page::where('slug', 'home')->sole();
        $this->assertFalse($home->is_indexable);
        $this->assertSame('Owner copy', $home->body);
        $this->assertSame('Owner text', Article::where('slug', 'owner-article')->sole()->body);
        $article = Article::where('slug', 'company-registration-and-compliance-zimbabwe')->sole();
        $this->get(route('articles.show', $article))->assertOk()->assertSee('Registration and tax compliance are different tasks')
            ->assertSee('href="/services/tin-tax-clearance"', false)->assertDontSee('**CTA:**');
    }
}
