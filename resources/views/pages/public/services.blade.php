<x-layouts::public
    :title="__('Services')"
    :meta-description="__('Company registration, tax and fiscal compliance, tenders, employer compliance, trusts and licences — every ITM service with its requirements explained.')"
>
    <x-public.page-header
        :eyebrow="__('Services')"
        :heading="__('Every service, grouped by the outcome it delivers.')"
        :lead="__('Requirements are listed on each page before you commit. A consultant confirms the final list in your written quotation.')"
    />

    <div class="page-shell-wide section-tight">
        @foreach ($categories as $category)
            @continue($category->services->isEmpty())

            <section class="mb-14 last:mb-0" aria-labelledby="cat-{{ $category->slug }}">
                <div class="flex flex-wrap items-end justify-between gap-4 border-b border-line pb-4">
                    <div class="flex items-center gap-3">
                        <span class="flex size-10 items-center justify-center rounded-control bg-teal-100 text-teal">
                            <x-ui.icon :name="$category->icon ?? 'folder'" size="20px" />
                        </span>
                        <div>
                            <h2 id="cat-{{ $category->slug }}" class="text-h3 font-semibold tracking-tight">{{ $category->name }}</h2>
                            <p class="card-meta">{{ trans_choice(':count service|:count services', $category->services->count()) }}</p>
                        </div>
                    </div>

                    <a href="{{ route('services.category', $category) }}" class="text-meta font-semibold text-teal">
                        {{ __('About this category') }} &rarr;
                    </a>
                </div>

                <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-3" data-reveal-stagger="60">
                    @foreach ($category->services as $service)
                        <a href="{{ route('services.show', $service) }}" class="card card-lift group flex flex-col no-underline hover:no-underline" data-reveal="up">
                            <h3 class="font-semibold text-navy">{{ $service->name }}</h3>
                            <p class="card-body mt-2 flex-1">{{ Str::limit($service->summary, 110) }}</p>
                            <span class="mt-4 inline-flex items-center gap-2 text-meta font-semibold text-teal">
                                {{ __('View service') }}
                                <x-ui.icon name="arrow-right" size="14px" class="transition-transform duration-300 group-hover:translate-x-1" />
                            </span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>

    <x-public.cta />
</x-layouts::public>
