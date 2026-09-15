<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    private function category(array $attributes = []): ServiceCategory
    {
        return ServiceCategory::create(array_merge([
            'slug' => 'company-registration',
            'name' => 'Company Registration',
            'summary' => 'Move from an idea to a registered business.',
            'is_published' => true,
        ], $attributes));
    }

    private function service(ServiceCategory $category, array $attributes = []): Service
    {
        return Service::create(array_merge([
            'service_category_id' => $category->id,
            'slug' => 'private-limited-company-registration',
            'name' => 'Private Limited Company Registration',
            'summary' => 'Register a private limited company in Zimbabwe.',
            'intro' => 'Move from a business idea to a registered company.',
            'requirements' => ['Proposed company names', 'Director details'],
            'steps' => ['Complete the questionnaire', 'A consultant checks it'],
            'is_published' => true,
        ], $attributes));
    }

    public function test_the_homepage_lists_published_categories(): void
    {
        $this->category();

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Company Registration');
    }

    public function test_the_homepage_hides_unpublished_categories(): void
    {
        $this->category(['name' => 'Hidden Category', 'slug' => 'hidden', 'is_published' => false]);

        $this->get(route('home'))->assertDontSee('Hidden Category');
    }

    public function test_a_service_page_renders_its_structured_content(): void
    {
        $service = $this->service($this->category());

        $this->get(route('services.show', $service))
            ->assertOk()
            ->assertSee('Private Limited Company Registration')
            ->assertSee('Proposed company names')
            ->assertSee('Complete the questionnaire');
    }

    public function test_an_unpublished_service_is_not_reachable(): void
    {
        $service = $this->service($this->category(), ['is_published' => false]);

        $this->get(route('services.show', $service))->assertNotFound();
    }

    public function test_a_service_page_emits_structured_data(): void
    {
        $service = $this->service($this->category());
        $service->faqs()->create(['question' => 'Is approval guaranteed?', 'answer' => 'No.']);

        $response = $this->get(route('services.show', $service));

        $response->assertSee('application/ld+json', false)
            ->assertSee('"@type":"Service"', false)
            ->assertSee('"@type":"FAQPage"', false);
    }

    /** Charges must never show an invented amount — the board forbids it. */
    public function test_a_service_page_never_shows_an_invented_fee(): void
    {
        $service = $this->service($this->category());

        $this->get(route('services.show', $service))
            ->assertSee('Quoted after assessment')
            ->assertDontSee('US$1')
            ->assertSee('Payment is requested only after you approve');
    }

    public function test_a_category_page_lists_only_its_published_services(): void
    {
        $category = $this->category();
        $this->service($category);
        $this->service($category, ['slug' => 'draft-service', 'name' => 'Draft Service', 'is_published' => false]);

        $this->get(route('services.category', $category))
            ->assertOk()
            ->assertSee('Private Limited Company Registration')
            ->assertDontSee('Draft Service');
    }

    public function test_the_articles_hub_and_article_pages_render(): void
    {
        $article = Article::create([
            'slug' => 'what-is-a-tin-zimbabwe',
            'title' => 'What Is a TIN?',
            'excerpt' => 'A short explanation.',
            'body' => "## Overview\n\nA TIN identifies a taxpayer.",
            'is_published' => true,
        ]);

        $this->get(route('articles.index'))->assertOk()->assertSee('What Is a TIN?');

        $this->get(route('articles.show', $article))
            ->assertOk()
            ->assertSee('A TIN identifies a taxpayer')
            ->assertSee('"@type":"Article"', false);
    }

    public function test_an_editable_page_renders_from_the_database(): void
    {
        Page::create([
            'slug' => 'about',
            'title' => 'About ITM',
            'body' => 'We help Zimbabwean businesses stay compliant.',
            'is_published' => true,
        ]);

        $this->get(route('page', 'about'))
            ->assertOk()
            ->assertSee('We help Zimbabwean businesses stay compliant.');
    }

    public function test_an_unpublished_page_returns_not_found(): void
    {
        Page::create(['slug' => 'draft', 'title' => 'Draft', 'is_published' => false]);

        $this->get(route('page', 'draft'))->assertNotFound();
    }

    public function test_the_faq_page_renders_site_wide_questions(): void
    {
        Page::create(['slug' => 'faq', 'title' => 'FAQs', 'is_published' => true]);
        Faq::create(['question' => 'Do you guarantee approval?', 'answer' => 'No.']);

        $this->get(route('page', 'faq'))
            ->assertOk()
            ->assertSee('Do you guarantee approval?');
    }

    public function test_the_sitemap_lists_published_content_only(): void
    {
        $category = $this->category();
        $this->service($category);
        $this->service($category, ['slug' => 'hidden-service', 'name' => 'Hidden', 'is_published' => false]);

        $this->get(route('sitemap'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee('private-limited-company-registration')
            ->assertDontSee('hidden-service');
    }
}
