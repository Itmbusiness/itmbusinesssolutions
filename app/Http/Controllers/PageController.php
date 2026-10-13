<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Package;
use App\Models\Page;
use App\Models\Service;
use App\Support\MarkdownDocument;
use Illuminate\View\View;

class PageController extends Controller
{
    /** Slugs that have their own route and template rather than the generic one. */
    private const RESERVED = ['home', 'services', 'packages'];

    public function show(string $slug): View
    {
        abort_if(in_array($slug, self::RESERVED, true), 404);

        $page = Page::published()->where('slug', $slug)->firstOrFail();

        // A short lead paragraph, if the body opens with one, moves into the
        // navy header alongside the title — the same treatment service and
        // category pages give their intro text — instead of sitting as the
        // first line of plain prose beneath it. Only the opening paragraph is
        // taken: a page whose first block is (or starts with) a list, or has
        // more than one paragraph before its first heading, keeps the rest as
        // an ordinary content block.
        $blocks = MarkdownDocument::parse($page->body ?? '')->blocks();
        $lead = null;

        if ($blocks !== [] && $blocks[0]['heading'] === null) {
            $paragraphs = preg_split('/\n{2,}/', trim($blocks[0]['body'])) ?: [];
            $firstParagraph = trim($paragraphs[0] ?? '');

            if ($firstParagraph !== '' && preg_match('/^(?:[-*+]|\d+\.)\s+/m', $firstParagraph) !== 1) {
                $lead = MarkdownDocument::plain($firstParagraph);
                $remainder = trim(implode("\n\n", array_slice($paragraphs, 1)));

                if ($remainder === '') {
                    array_shift($blocks);
                } else {
                    $blocks[0] = ['heading' => null, 'body' => $remainder];
                }
            }
        }

        if ($slug === 'contact') {
            // These imported sections describe UI and duplicate Settings values.
            // The contact template supplies the real form and live channels.
            $blocks = array_values(array_filter($blocks, fn (array $block) => ! in_array(
                strtolower(trim($block['heading'] ?? '')),
                ['contact form', 'contact channels'],
                true,
            )));
        }

        return view($slug === 'contact' ? 'pages.public.contact' : 'pages.public.page', [
            'page' => $page,
            'lead' => $lead,
            'blocks' => $blocks,
            'contactServices' => $slug === 'contact'
                ? Service::published()->whereHas('category', fn ($query) => $query->where('is_published', true))
                    ->ordered()->with('category')->get()->groupBy(fn (Service $service) => $service->category->name)
                : collect(),
            'checklists' => $slug === 'requirements'
                ? Service::published()->whereHas('category', fn ($query) => $query->where('is_published', true))->ordered()->with('category')->get()
                : collect(),
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
