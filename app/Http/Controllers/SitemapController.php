<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Page;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = [
            ['loc' => route('home'), 'priority' => '1.0', 'lastmod' => null],
            ['loc' => route('services.index'), 'priority' => '0.9', 'lastmod' => null],
            ['loc' => route('packages'), 'priority' => '0.8', 'lastmod' => null],
            ['loc' => route('articles.index'), 'priority' => '0.7', 'lastmod' => null],
        ];

        foreach (ServiceCategory::published()->ordered()->get() as $category) {
            $urls[] = [
                'loc' => route('services.category', $category),
                'priority' => '0.8',
                'lastmod' => $category->updated_at?->toAtomString(),
            ];
        }

        foreach (Service::published()->ordered()->get() as $service) {
            $urls[] = [
                'loc' => route('services.show', $service),
                'priority' => '0.9',
                'lastmod' => $service->updated_at?->toAtomString(),
            ];
        }

        foreach (Article::published()->get() as $article) {
            $urls[] = [
                'loc' => route('articles.show', $article),
                'priority' => '0.6',
                'lastmod' => $article->updated_at?->toAtomString(),
            ];
        }

        foreach (Page::published()->where('is_indexable', true)->get() as $page) {
            if (in_array($page->slug, ['home', 'services', 'packages'], true)) {
                continue;
            }

            $urls[] = [
                'loc' => route('page', $page->slug),
                'priority' => '0.4',
                'lastmod' => $page->updated_at?->toAtomString(),
            ];
        }

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
