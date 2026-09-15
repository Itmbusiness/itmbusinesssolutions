<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ServiceCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        return view('pages.admin.articles.index', [
            'articles' => Article::query()
                ->when($request->filled('q'), fn ($query) => $query->where('title', 'like', '%'.$request->string('q').'%'))
                ->with('category')
                ->orderBy('position')
                ->paginate(30)
                ->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('pages.admin.articles.form', [
            'article' => new Article(['is_published' => true]),
            'categories' => ServiceCategory::ordered()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $article = Article::create($this->validated($request));

        return redirect()->route('admin.articles.edit', $article)->with('status', 'Article created.');
    }

    public function edit(Article $article): View
    {
        return view('pages.admin.articles.form', [
            'article' => $article,
            'categories' => ServiceCategory::ordered()->get(),
        ]);
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $article->update($this->validated($request, $article));

        return back()->with('status', 'Article saved.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();

        return redirect()->route('admin.articles.index')->with('status', 'Article deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Article $article = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9-]+$/', Rule::unique('articles', 'slug')->ignore($article)],
            'service_category_id' => ['nullable', 'exists:service_categories,id'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'primary_keyword' => ['nullable', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:255'],
            'reviewer' => ['nullable', 'string', 'max:255'],
            'reviewed_on' => ['nullable', 'date'],
            'next_review_date' => ['nullable', 'date'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['slug'] = ($validated['slug'] ?? null) ?: Str::slug($validated['title']);
        $validated['is_published'] = $request->boolean('is_published');
        $validated['position'] = $validated['position'] ?? 0;

        if ($validated['is_published'] && $article?->published_at === null) {
            $validated['published_at'] = now();
        }

        return $validated;
    }
}
