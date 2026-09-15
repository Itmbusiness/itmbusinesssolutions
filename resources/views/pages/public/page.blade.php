<x-layouts::public
    :title="$page->seo_title ?? $page->title"
    :meta-description="$page->meta_description"
    :indexable="$page->is_indexable"
>
    <x-public.page-header :heading="$page->title" />

    <div class="page-shell section-tight">
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
            <article class="prose-itm">
                {!! Str::markdown($page->body ?? '') !!}
            </article>
        @endif
    </div>

    <x-public.cta />
</x-layouts::public>
