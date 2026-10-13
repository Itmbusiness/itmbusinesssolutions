<x-layouts::public
    :title="$page->seo_title ?? $page->title"
    :meta-description="$page->meta_description"
    :indexable="$page->is_indexable"
>
    <x-public.page-header :heading="$page->title" :lead="$lead" :image="in_array($page->slug, ['about', 'requirements', 'contact']) ? ($page->slug === 'about' ? 'registration' : $page->slug) : null" />

    <div class="page-shell section-tight">
        @if (in_array($page->slug, ['privacy', 'terms']))
            <x-public.legal-content :blocks="$blocks" />
        @else
        @if ($page->slug === 'requirements')
            <x-public.checklist-finder :services="$checklists" />
        @endif
        @if ($faqs->isNotEmpty())
            <div class="flex flex-col gap-2">
                @foreach ($faqs as $faq)
                    <details class="card group">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-navy">
                            {{ $faq->question }}
                            <x-ui.icon name="plus" size="16px" class="flex-none text-teal transition-transform group-open:rotate-45" />
                        </summary>
                        <p class="card-body mt-3">{{ $faq->answer }}</p>
                    </details>
                @endforeach
            </div>
        @else
            <x-public.rich-content :blocks="$blocks" :illustrated="in_array($page->slug, ['about', 'requirements'])" :subject="$page->slug" />

            @if ($page->cta_label && $page->cta_url)
                <x-public.inline-cta :label="$page->cta_label" :url="$page->cta_url" class="mt-6" />
            @endif
        @endif
        @endif
    </div>

    @unless (in_array($page->slug, ['privacy', 'terms']))
        <x-public.cta />
    @endunless
</x-layouts::public>
