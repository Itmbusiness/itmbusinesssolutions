<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        return view('pages.admin.pages.index', ['pages' => Page::orderBy('title')->get()]);
    }

    public function edit(Page $page): View
    {
        return view('pages.admin.pages.form', ['page' => $page]);
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
        ]);

        $validated['is_published'] = $request->boolean('is_published');
        $validated['is_indexable'] = $request->boolean('is_indexable');

        $page->update($validated);

        return back()->with('status', 'Page saved.');
    }
}
