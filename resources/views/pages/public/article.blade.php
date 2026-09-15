<x-layouts::public
    :title="$article->seo_title ?? $article->title"
    :meta-description="$article->meta_description"
    :canonical="route('articles.show', $article)"
    :schema="$schema"
>
    <x-public.page-header
        :eyebrow="$article->category?->name ?? __('Resources')"
        :heading="$article->title"
        :lead="$article->excerpt"
        :breadcrumbs="[__('Resources') => route('articles.index'), $article->title => null]"
    />

    <div class="page-shell section-tight">
        @if ($article->isStale())
            <x-ui.alert variant="attention" title="{{ __('This article is due for review') }}" class="mb-8">
                {{ __('Rules and official processes change. Confirm the current position with a consultant before acting on this guidance.') }}
            </x-ui.alert>
        @endif

        <article class="prose-itm">
            {!! Str::markdown($article->body ?? '') !!}
        </article>

        <footer class="mt-10 flex flex-wrap items-center gap-x-6 gap-y-2 border-t border-line pt-5 text-meta text-muted">
            @if ($article->author)
                <span>{{ __('Written by') }} <span class="text-navy">{{ $article->author }}</span></span>
            @endif
            @if ($article->reviewer)
                <span>{{ __('Reviewed by') }} <span class="text-navy">{{ $article->reviewer }}</span></span>
            @endif
            @if ($article->reviewed_on)
                <span>{{ __('Reviewed') }} <span class="text-navy">{{ $article->reviewed_on->format('j M Y') }}</span></span>
            @endif
        </footer>

        @if ($related->isNotEmpty())
            <section class="mt-12" aria-labelledby="related-articles">
                <h2 id="related-articles" class="text-h3 font-semibold tracking-tight">{{ __('Related reading') }}</h2>
                <div class="mt-4 grid gap-3 sm:grid-cols-3">
                    @foreach ($related as $other)
                        <a href="{{ route('articles.show', $other) }}" class="card card-lift no-underline hover:no-underline">
                            <h3 class="font-semibold text-navy">{{ $other->title }}</h3>
                            <p class="card-body mt-2">{{ Str::limit($other->excerpt, 90) }}</p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>

    <x-public.cta />
</x-layouts::public>
