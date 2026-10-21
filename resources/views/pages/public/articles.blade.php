<x-layouts::public
    :title="$page?->seo_title ?? __('Resources')"
    :meta-description="$page?->meta_description ?? __('Plain-language guidance on company registration, tax, NSSA, tenders and organisation structures in Zimbabwe.')"
    :indexable="$page?->is_indexable ?? true"
>
    <x-public.page-header
        image="resources"
        :eyebrow="__('Resources')"
        :heading="__('Practical guidance for your next step')"
        :lead="__('Practical explanations of the questions clients ask most. Guidance is general — a consultant confirms what applies to your organisation.')"
    />

    <div class="page-shell-wide section-tight">
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3" data-reveal-stagger="70">
            @forelse ($articles as $article)
                <a href="{{ route('articles.show', $article) }}" class="card card-lift public-image-card group flex flex-col no-underline hover:no-underline" data-reveal="up">
                    <x-public.media :subject="$article->slug" class="public-card-image" />
                    @if ($article->category)
                        <p class="card-kicker text-teal">{{ $article->category->name }}</p>
                    @endif
                    <h2 class="card-title mt-3 text-balance text-navy">{{ $article->title }}</h2>
                    <p class="card-body mt-2 flex-1">{{ $article->excerpt }}</p>
                    <span class="mt-6 flex items-center gap-2 border-t border-line-soft pt-4 text-meta text-muted">
                        <x-ui.icon name="clock-counter-clockwise" size="14px" />
                        {{ $article->reviewed_on?->format('j M Y') ?? __('Check current requirements') }}
                    </span>
                </a>
            @empty
                <div class="md:col-span-2 lg:col-span-3">
                    <x-ui.empty-state :title="__('No articles published yet')">
                        {{ __('Guidance articles will appear here once published.') }}
                    </x-ui.empty-state>
                </div>
            @endforelse
        </div>
    </div>

    <x-public.cta />
</x-layouts::public>
