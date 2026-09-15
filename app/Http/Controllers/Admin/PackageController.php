<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(): View
    {
        return view('pages.admin.packages.index', ['packages' => Package::ordered()->get()]);
    }

    public function create(): View
    {
        return view('pages.admin.packages.form', ['package' => new Package(['is_published' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $package = Package::create($this->validated($request));

        return redirect()->route('admin.packages.edit', $package)->with('status', 'Package created.');
    }

    public function edit(Package $package): View
    {
        return view('pages.admin.packages.form', ['package' => $package]);
    }

    public function update(Request $request, Package $package): RedirectResponse
    {
        $package->update($this->validated($request, $package));

        return back()->with('status', 'Package saved.');
    }

    public function destroy(Package $package): RedirectResponse
    {
        $package->delete();

        return redirect()->route('admin.packages.index')->with('status', 'Package deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Package $package = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9-]+$/', Rule::unique('packages', 'slug')->ignore($package)],
            'summary' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'includes' => ['nullable', 'string'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['slug'] = ($validated['slug'] ?? null) ?: Str::slug($validated['name']);
        $validated['is_published'] = $request->boolean('is_published');
        $validated['position'] = $validated['position'] ?? 0;
        $validated['includes'] = array_values(array_filter(array_map(
            'trim',
            preg_split('/\R/', $validated['includes'] ?? '') ?: [],
        )));

        return $validated;
    }
}
