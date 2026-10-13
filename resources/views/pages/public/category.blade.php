<x-layouts::public
    :title="$category->seo_title ?? $category->name"
    :meta-description="$category->meta_description ?? $category->summary"
    :canonical="route('services.category', $category)"
>
    <x-public.page-header
        :image="$category->slug"
        :eyebrow="__('Services')"
        :heading="$category->name"
        :lead="$category->summary"
        :breadcrumbs="[__('Services') => route('services.index'), $category->name => null]"
    />

    <div class="page-shell-wide section-tight">
        <div class="grid gap-12 lg:grid-cols-[minmax(0,1fr)_300px]">
            <div class="min-w-0">
                <h2 class="text-h3 font-semibold tracking-tight">{{ __('Services in this category') }}</h2>

                <div class="mt-5 flex flex-col gap-3" data-reveal-stagger="70">
                    @forelse ($category->services as $service)
                        <a href="{{ route('services.show', $service) }}" class="card card-lift public-service-row group no-underline hover:no-underline" data-reveal="up">
                            <x-public.media :subject="$service->slug" class="public-service-row-image" />
                            <div class="flex items-start justify-between gap-4">
                                <h3 class="text-h3 font-semibold text-navy">{{ $service->name }}</h3>
                                <x-ui.icon name="arrow-right" size="18px" class="mt-1 flex-none text-teal transition-transform duration-300 group-hover:translate-x-1" />
                            </div>
                            <p class="card-body mt-2">{{ $service->summary }}</p>
                        </a>
                    @empty
                        <x-ui.empty-state :title="__('No services published yet')">
                            {{ __('Services in this category will appear here once published.') }}
                        </x-ui.empty-state>
                    @endforelse
                </div>

                @if ($category->body)
                    <section class="mt-12">
                        <h2 class="text-h3 font-semibold tracking-tight">{{ __('How the quotation process works') }}</h2>
                        <div class="prose-itm mt-4">{!! Str::markdown($category->body) !!}</div>
                    </section>
                @endif
            </div>

            <aside class="flex flex-col gap-4 lg:sticky lg:top-24 lg:self-start">
                @if (filled($category->audience))
                    <x-ui.card>
                        <p class="card-kicker text-teal">{{ __('Who this helps') }}</p>
                        <ul class="mt-3 flex flex-col gap-2.5">
                            @foreach ($category->audience as $item)
                                <li class="flex gap-2.5 text-meta text-ink">
                                    <x-ui.icon name="check" size="15px" class="mt-0.5 flex-none text-teal" />
                                    <span>{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </x-ui.card>
                @endif

                @if ($articles->isNotEmpty())
                    <x-ui.card>
                        <p class="card-kicker">{{ __('Helpful reading') }}</p>
                        <ul class="mt-3 flex flex-col gap-2.5">
                            @foreach ($articles as $article)
                                <li><a href="{{ route('articles.show', $article) }}" class="text-meta font-medium">{{ $article->title }}</a></li>
                            @endforeach
                        </ul>
                    </x-ui.card>
                @endif
            </aside>
        </div>
    </div>

    <x-public.cta :heading="__('Not sure which service applies?')" :body="__('Describe the outcome you want. A consultant will confirm the right route before any quotation is approved.')" />
</x-layouts::public>
