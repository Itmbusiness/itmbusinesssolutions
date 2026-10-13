<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Support\MarkdownDocument;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class LegalPagesSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            foreach (['privacy' => 'privacy-policy', 'terms' => 'terms-of-service'] as $slug => $filename) {
                $path = base_path('docs/ITM_Website_PRD_and_Content_Pack/ITM_Website_PRD_Content_Pack/content/public/'.$filename.'.md');
                $raw = file_get_contents($path);

                if ($raw === false) {
                    throw new RuntimeException('Cannot read legal content: '.$filename);
                }

                $document = MarkdownDocument::parse($raw);
                $page = Page::firstOrNew(['slug' => $slug]);

                if (! $page->exists) {
                    $page->fill(['is_published' => true, 'is_indexable' => true, 'is_system' => true]);
                }

                $page->fill([
                    'title' => $document->title(),
                    'seo_title' => $document->frontMatter('seo_title'),
                    'meta_description' => $document->frontMatter('meta_description'),
                    'body' => $document->body,
                ])->save();
            }
        });
    }
}
