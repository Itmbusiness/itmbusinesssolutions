<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServiceCategoryController extends Controller
{
    public function index(): View
    {
        return view('pages.admin.categories.index', [
            'categories' => ServiceCategory::ordered()->withCount('services')->get(),
        ]);
    }

    public function create(): View
    {
        return view('pages.admin.categories.form', ['category' => new ServiceCategory(['is_published' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $category = ServiceCategory::create($this->validated($request));

        return redirect()->route('admin.categories.edit', $category)->with('status', 'Category created.');
    }

    public function edit(ServiceCategory $category): View
    {
        return view('pages.admin.categories.form', ['category' => $category]);
    }

    public function update(Request $request, ServiceCategory $category): RedirectResponse
    {
        $category->update($this->validated($request, $category));

        return back()->with('status', 'Category saved.');
    }

    public function destroy(ServiceCategory $category): RedirectResponse
    {
        if ($category->services()->exists()) {
            return back()->withErrors([
                'category' => 'Move or delete this category\'s services before deleting it.',
            ]);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('status', 'Category deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?ServiceCategory $category = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9-]+$/', Rule::unique('service_categories', 'slug')->ignore($category)],
            'summary' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'audience' => ['nullable', 'string'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'primary_keyword' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['slug'] = ($validated['slug'] ?? null) ?: Str::slug($validated['name']);
        $validated['is_published'] = $request->boolean('is_published');
        $validated['position'] = $validated['position'] ?? 0;
        $validated['audience'] = array_values(array_filter(array_map(
            'trim',
            preg_split('/\R/', $validated['audience'] ?? '') ?: [],
        )));

        return $validated;
    }
}
