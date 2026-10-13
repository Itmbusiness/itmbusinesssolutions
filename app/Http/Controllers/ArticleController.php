<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Page;
use App\Support\MarkdownDocument;
use App\Support\Seo;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        return view('pages.public.articles', [
            'page' => Page::published()->where('slug', 'resources')->first(),
            'articles' => Article::published()->with('category')->orderBy('position')->get(),
        ]);
    }

    public function show(Article $article): View
    {
        abort_unless($article->is_published, 404);

        $article->load('category');

        // The header already shows the article's own excerpt as its lead, so
        // an opening paragraph with no heading of its own — the pack's
        // boilerplate intro line, on every article — is dropped rather than
        // repeated a second time as the first block of the body.
        $blocks = MarkdownDocument::parse($article->body ?? '')->blocks();

        if ($blocks !== [] && $blocks[0]['heading'] === null) {
            array_shift($blocks);
        }

        return view('pages.public.article', [
            'article' => $article,
            'blocks' => $blocks,
            'related' => Article::published()
                ->whereKeyNot($article->getKey())
                ->when($article->service_category_id, fn ($query) => $query->where('service_category_id', $article->service_category_id))
                ->limit(3)
                ->get(),
            'schema' => [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $article->title,
                'description' => $article->excerpt,
                'datePublished' => $article->published_at?->toIso8601String(),
                'dateModified' => $article->updated_at?->toIso8601String(),
                'author' => ['@type' => 'Organization', 'name' => $article->author ?? config('app.name')],
                'publisher' => ['@type' => 'Organization', 'name' => config('app.name')],
                'mainEntityOfPage' => Seo::url(route('articles.show', $article)),
            ],
        ]);
    }
}
