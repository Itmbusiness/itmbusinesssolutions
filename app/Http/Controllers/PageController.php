<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Package;
use App\Models\Page;
use Illuminate\View\View;

class PageController extends Controller
{
    /** Slugs that have their own route and template rather than the generic one. */
    private const RESERVED = ['home', 'services', 'packages'];

    public function show(string $slug): View
    {
        abort_if(in_array($slug, self::RESERVED, true), 404);

        $page = Page::published()->where('slug', $slug)->firstOrFail();

        return view('pages.public.page', [
            'page' => $page,
            // The FAQ page renders its questions as components, not raw body.
            'faqs' => $slug === 'faq' ? Faq::published()->global()->orderBy('position')->get() : collect(),
        ]);
    }

    public function packages(): View
    {
        return view('pages.public.packages', [
            'page' => Page::published()->where('slug', 'packages')->first(),
            'packages' => Package::published()->ordered()->get(),
        ]);
    }
}
