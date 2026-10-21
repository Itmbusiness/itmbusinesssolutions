<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EditorialContentRepairTest extends TestCase
{
    use RefreshDatabase;

    public function test_editorial_repair_updates_imported_copy_without_overwriting_owner_edits(): void
    {
        $about = Page::create([
            'slug' => 'about',
            'title' => 'About',
            'body' => 'Our details. Production content must insert the verified legal entity name, registration number, consultant authorisation where applicable, physical address, contact information and real team profiles.',
        ]);
        $ownerPage = Page::create(['slug' => 'contact', 'title' => 'Contact', 'body' => 'Owner-written contact copy.']);
        $article = Article::create([
            'slug' => 'pbc-vs-private-limited-company-zimbabwe',
            'title' => 'PBC vs private limited company',
            'body' => 'Explain ownership terminology, governance, administrative expectations and perceived suitability using current reviewed information. Avoid claiming that one structure is automatically better for every founder.',
            'excerpt' => 'A practical guide to pbc vs private limited company in zimbabwe, including preparation questions, common risks and the next appropriate step.',
            'meta_description' => 'A practical guide to pbc vs private limited company in zimbabwe, including preparation questions, common risks and the next appropriate step.',
        ]);
        DB::table('faqs')->insert([
            'question' => 'Is company re-registration still open?',
            'answer' => 'Availability, deadlines and consequences must be checked against a current official notice. The page should display a dated status banner maintained by the content team.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $migration = require database_path('migrations/2026_10_21_120000_repair_public_editorial_copy.php');
        $migration->up();
        $migration->up();

        $this->assertStringContainsString('see our [Contact page](/contact)', $about->fresh()->body);
        $this->assertSame('Owner-written contact copy.', $ownerPage->fresh()->body);
        $this->assertStringContainsString('A PBC and a private limited company', $article->fresh()->body);
        $this->assertStringStartsWith('Compare PBC and private limited', $article->fresh()->excerpt);
        $this->assertSame($article->fresh()->excerpt, $article->fresh()->meta_description);
        $this->assertStringStartsWith('Please ask us to check', DB::table('faqs')->value('answer'));
    }

    public function test_inline_call_to_action_has_one_label_and_one_link(): void
    {
        $html = Blade::render('<x-public.inline-cta label="Compare Structures" url="/services" />');

        $this->assertSame(1, substr_count($html, 'Compare Structures'));
        $this->assertSame(1, substr_count($html, 'href="/services"'));
    }

    public function test_article_cards_do_not_claim_an_unrecorded_review_or_cut_off_excerpts(): void
    {
        $this->withoutVite();
        Article::create([
            'slug' => 'preparation-guide',
            'title' => 'Preparation guide',
            'excerpt' => 'Prepare the records for your organisation before contacting ITM Business Solutions about the service you need.',
            'is_published' => true,
        ]);

        $this->get('/articles')->assertOk()
            ->assertSee('Prepare the records for your organisation before contacting ITM Business Solutions about the service you need.')
            ->assertSee('Check current requirements')
            ->assertDontSee('Reviewed before publication');
    }
}
