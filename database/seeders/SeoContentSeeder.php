<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Page;
use App\Models\ServiceCategory;
use App\Support\MarkdownDocument;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeoContentSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $root = base_path('docs/ITM_Website_PRD_and_Content_Pack/ITM_Website_PRD_Content_Pack/content/');
            $home = MarkdownDocument::parse((string) file_get_contents($root.'public/home.md'));
            $page = Page::firstOrNew(['slug' => 'home']);
            if (! $page->exists) {
                $page->fill(['is_system' => true, 'is_published' => true, 'is_indexable' => true]);
            }
            $page->fill([
                'title' => $home->title(), 'seo_title' => $home->frontMatter('seo_title'),
                'meta_description' => $home->frontMatter('meta_description'),
            ])->save();

            $doc = MarkdownDocument::parse((string) file_get_contents($root.'articles/company-registration-and-compliance-zimbabwe.md'));
            $article = Article::firstOrNew(['slug' => 'company-registration-and-compliance-zimbabwe']);
            if (! $article->exists) {
                $article->fill(['is_published' => true, 'published_at' => now(), 'position' => 0]);
            }
            $article->fill([
                'title' => $doc->title(), 'excerpt' => $doc->intro(),
                'body' => trim(str_replace('**CTA:** Request a Written Quotation', '', $doc->body)),
                'seo_title' => $doc->frontMatter('seo_title'), 'meta_description' => $doc->frontMatter('meta_description'),
                'primary_keyword' => $doc->frontMatter('primary_keyword'), 'author' => $doc->frontMatter('author'),
                'service_category_id' => ServiceCategory::where('slug', 'company-registration')->value('id'),
                'cta_label' => 'Request a Written Quotation', 'cta_url' => '/request-a-quotation',
            ])->save();
        });
    }
}
