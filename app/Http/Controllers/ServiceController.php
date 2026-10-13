<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Support\Seo;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('pages.public.services', [
            'page' => Page::published()->where('slug', 'services')->first(),
            'categories' => ServiceCategory::published()
                ->ordered()
                ->with(['services' => fn ($query) => $query->published()])
                ->get(),
        ]);
    }

    public function category(ServiceCategory $category): View
    {
        abort_unless($category->is_published, 404);

        $category->load(['services' => fn ($query) => $query->published()]);

        return view('pages.public.category', [
            'category' => $category,
            'articles' => $category->articles()->published()->limit(3)->get(),
        ]);
    }

    public function show(Service $service): View
    {
        abort_unless($service->is_published && $service->category->is_published, 404);

        $service->load(['category', 'faqs' => fn ($query) => $query->published(), 'relatedServices' => fn ($query) => $query->published()]);

        return view('pages.public.service', [
            'service' => $service,
            'schema' => $this->schema($service),
        ]);
    }

    /**
     * Service and FAQPage structured data. FAQ entries are only emitted for
     * FAQs that are actually visible on the page, per the PRD's SEO rules.
     *
     * @return array<string, mixed>
     */
    private function schema(Service $service): array
    {
        $graph = [[
            '@type' => 'Service',
            'name' => $service->name,
            'description' => $service->summary,
            'serviceType' => $service->category->name,
            'areaServed' => ['@type' => 'Country', 'name' => 'Zimbabwe'],
            'provider' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
                '@id' => Seo::url(url('/')).'#organization',
                'url' => Seo::url(url('/')),
            ],
            'url' => Seo::url(route('services.show', $service)),
        ], [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Services', 'item' => Seo::url(route('services.index'))],
                ['@type' => 'ListItem', 'position' => 2, 'name' => $service->category->name, 'item' => Seo::url(route('services.category', $service->category))],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $service->name, 'item' => Seo::url(route('services.show', $service))],
            ],
        ]];

        if ($service->faqs->isNotEmpty()) {
            $graph[] = [
                '@type' => 'FAQPage',
                'mainEntity' => $service->faqs->map(fn ($faq) => [
                    '@type' => 'Question',
                    'name' => $faq->question,
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq->answer],
                ])->all(),
            ];
        }

        return ['@context' => 'https://schema.org', '@graph' => $graph];
    }
}
