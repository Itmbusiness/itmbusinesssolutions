<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        return view('pages.admin.services.index', [
            'services' => Service::query()
                ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->string('q').'%'))
                ->with('category')
                ->ordered()
                ->paginate(30)
                ->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('pages.admin.services.form', [
            'service' => new Service(['is_published' => true]),
            'categories' => ServiceCategory::ordered()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $service = Service::create($this->validated($request));

        return redirect()->route('admin.services.edit', $service)->with('status', 'Service created.');
    }

    public function edit(Service $service): View
    {
        return view('pages.admin.services.form', [
            'service' => $service,
            'categories' => ServiceCategory::ordered()->get(),
        ]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $service->update($this->validated($request, $service));

        return back()->with('status', 'Service saved.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('status', 'Service deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Service $service = null): array
    {
        $validated = $request->validate([
            'service_category_id' => ['required', 'exists:service_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9-]+$/', Rule::unique('services', 'slug')->ignore($service)],
            'summary' => ['nullable', 'string', 'max:500'],
            'intro' => ['nullable', 'string', 'max:2000'],
            'suited_to' => ['nullable', 'string'],
            'includes' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'steps' => ['nullable', 'string'],
            'charges_note' => ['nullable', 'string'],
            'processing_note' => ['nullable', 'string'],
            'source_note' => ['nullable', 'string'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'primary_keyword' => ['nullable', 'string', 'max:255'],
            'reviewer' => ['nullable', 'string', 'max:255'],
            'reviewed_on' => ['nullable', 'date'],
            'next_review_date' => ['nullable', 'date'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['slug'] = ($validated['slug'] ?? null) ?: Str::slug($validated['name']);
        $validated['is_published'] = $request->boolean('is_published');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['position'] = $validated['position'] ?? 0;

        // The list fields are edited as one-per-line textareas.
        foreach (['suited_to', 'includes', 'requirements', 'steps'] as $field) {
            $validated[$field] = self::lines($validated[$field] ?? null);
        }

        return $validated;
    }

    /**
     * @return array<int, string>
     */
    private static function lines(?string $value): array
    {
        if ($value === null || trim($value) === '') {
            return [];
        }

        return array_values(array_filter(array_map('trim', preg_split('/\R/', $value) ?: [])));
    }
}
