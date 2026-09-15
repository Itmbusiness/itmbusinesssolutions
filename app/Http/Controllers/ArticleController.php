<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        return view('pages.public.articles', [
            'articles' => Article::published()->with('category')->orderBy('position')->get(),
        ]);
    }

    public function show(Article $article): View
    {
        abort_unless($article->is_published, 404);

        $article->load('category');

        return view('pages.public.article', [
            'article' => $article,
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
                'mainEntityOfPage' => route('articles.show', $article),
            ],
        ]);
    }
}
