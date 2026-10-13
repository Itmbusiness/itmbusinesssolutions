@php
    $headline = $page?->title ?? 'Start, Grow and Keep Your Organisation Compliant.';
@endphp

<x-layouts::public
    :title="$page?->seo_title ?? __('Zimbabwean registration & compliance')"
    :meta-description="$page?->meta_description ?? __('Company registration, tax, NSSA, PRAZ, licensing and business documents — with clear requirements, a written quotation before you pay, and visible progress.')"
    :indexable="$page?->is_indexable ?? true"
>
    {{-- ── Hero ────────────────────────────────────────────────── --}}
    <section class="ground-navy edge-light public-hero public-hero-image public-home-hero">
        <x-public.media subject="registration" priority class="public-hero-backdrop" />

        <div class="page-shell-wide public-hero-content grid items-center gap-10 pb-14 pt-14 lg:grid-cols-[1fr_0.65fr_0.45fr] lg:pb-20 lg:pt-20">
            <div>
                <p class="eyebrow-rule text-teal-300" data-reveal="fade">
                    {{ __('Zimbabwean registration & compliance') }}
                </p>

                <h1 class="public-heading mt-6 text-balance font-semibold text-white">
                    @foreach (explode(' ', $headline) as $i => $word)
                        <span class="word-line"><span data-reveal="word" style="--reveal-delay: {{ 120 + $i * 70 }}ms">{{ $word }}</span></span>{{ ' ' }}
                    @endforeach
                </h1>

                <p class="mt-7 max-w-[56ch] text-pretty text-[1.0625rem] leading-relaxed text-navy-300" data-reveal="up" data-reveal-delay="620">
                    {{ __('Company registration, tax, NSSA, PRAZ, licensing and business documents — with clear requirements, a written quotation before you pay, and visible progress from start to finish.') }}
                </p>

                <div class="mt-9 flex flex-wrap items-center gap-3" data-reveal="up" data-reveal-delay="720">
                    <x-ui.button href="{{ route('services.index') }}" icon-trailing="arrow-right">
                        {{ __('Find the Right Service') }}
                    </x-ui.button>
                    @if ($whatsapp = \App\Models\Setting::get('contact.whatsapp'))
                        <x-ui.button variant="secondary" icon="whatsapp-logo" href="{{ 'https://wa.me/'.preg_replace('/\D/', '', $whatsapp) }}" class="on-navy">
                            {{ __('Chat on WhatsApp') }}
                        </x-ui.button>
                    @endif
                </div>
            </div>

            <div class="glass public-promise-panel p-6" data-reveal="scale" data-reveal-delay="480">
                <p class="eyebrow-rule text-gold">{{ __('How we work') }}</p>

                <ul class="mt-6 flex flex-col gap-5" data-reveal-stagger="90">
                    @foreach ([
                        ['file-text', __('A written quotation first'), __('Nothing is charged until you approve an exact version.')],
                        ['list-checks', __('Fees separated, never bundled'), __('Professional, statutory and optional costs listed apart.')],
                        ['eye', __('Progress you can check'), __('Follow up using your enquiry reference.')],
                    ] as [$icon, $title, $body])
                        <li class="flex gap-4" data-reveal="up">
                            <span class="mt-0.5 flex size-9 flex-none items-center justify-center rounded-control bg-white/10 text-teal-300">
                                <x-ui.icon :name="$icon" size="18px" />
                            </span>
                            <span>
                                <span class="block font-semibold text-white">{{ $title }}</span>
                                <span class="mt-0.5 block text-meta leading-relaxed text-navy-300">{{ $body }}</span>
                            </span>
                        </li>
                    @endforeach
                </ul>

                <x-public.safety-note class="mt-7 border-t border-white/10 pt-5 text-navy-300" />
            </div>
        </div>
    </section>

    {{-- ── Scale strip ─────────────────────────────────────────── --}}
    <x-public.trust-strip />

    {{-- ── Outcomes ────────────────────────────────────────────── --}}
    <section class="section" aria-labelledby="outcomes-heading">
        <div class="page-shell-wide">
            <div class="max-w-[62ch]">
                <p class="eyebrow-rule text-teal" data-reveal="fade">{{ __('What would you like to achieve?') }}</p>
                <h2 id="outcomes-heading" class="public-subheading mt-5 text-balance font-semibold" data-reveal="up">
                    {{ __('Start from the outcome, not the paperwork.') }}
                </h2>
                <p class="mt-4 text-lead text-ink" data-reveal="up" data-reveal-delay="90">
                    {{ __('Pick the result you are after. Each route leads to the services that fit, with the requirements written out before you commit to anything.') }}
                </p>
            </div>

            <div class="mt-12 grid gap-4 sm:grid-cols-2 lg:grid-cols-3" data-reveal-stagger="80">
                @foreach ($categories as $category)
                    <article class="card card-lift public-image-card group relative flex flex-col" data-reveal="up">
                        <x-public.media :subject="$category->slug" class="public-card-image" />
                        <div class="flex items-start justify-between gap-3">
                            <span class="flex size-12 items-center justify-center rounded-control bg-teal-100 text-teal transition-colors duration-300 group-hover:bg-teal group-hover:text-white">
                                <x-ui.icon :name="$category->icon ?? 'folder'" size="24px" />
                            </span>
                            <span class="card-meta">{{ trans_choice(':count service|:count services', $category->services_count) }}</span>
                        </div>

                        <h3 class="mt-5 text-h3 font-semibold tracking-tight">{{ $category->name }}</h3>
                        <p class="card-body mt-2 flex-1">{{ $category->summary }}</p>

                        <p class="mt-6 inline-flex items-center gap-2 text-meta font-semibold text-teal">
                            {{ __('View services') }}
                            <x-ui.icon name="arrow-right" size="15px" class="transition-transform duration-300 group-hover:translate-x-1" />
                        </p>

                        <a href="{{ route('services.category', $category) }}" class="absolute inset-0" aria-label="{{ $category->name }}"></a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Popular services ────────────────────────────────────── --}}
    @if ($featured->isNotEmpty())
        <section class="section-tight border-y border-line bg-white" aria-labelledby="popular-heading">
            <div class="page-shell-wide">
                <div class="flex flex-wrap items-end justify-between gap-6">
                    <h2 id="popular-heading" class="text-h2 font-semibold tracking-tight" data-reveal="up">{{ __('Popular services') }}</h2>
                    <x-ui.button variant="ghost" href="{{ route('services.index') }}" icon-trailing="arrow-right" data-reveal="fade">
                        {{ __('All services') }}
                    </x-ui.button>
                </div>

                <div class="mt-8 grid gap-3 sm:grid-cols-2 lg:grid-cols-3" data-reveal-stagger="70">
                    @foreach ($featured as $service)
                        <a href="{{ route('services.show', $service) }}" class="card card-lift public-image-card group flex flex-col no-underline hover:no-underline" data-reveal="up">
                            <x-public.media :subject="$service->slug" class="public-card-image" />
                            <p class="card-kicker text-teal">{{ $service->category->name }}</p>
                            <h3 class="mt-2 font-semibold text-navy">{{ $service->name }}</h3>
                            <p class="card-body mt-2 flex-1">{{ Str::limit($service->summary, 110) }}</p>
                            <span class="mt-4 inline-flex items-center gap-2 text-meta font-semibold text-teal">
                                {{ __('View service') }}
                                <x-ui.icon name="arrow-right" size="14px" class="transition-transform duration-300 group-hover:translate-x-1" />
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ── Process ─────────────────────────────────────────────── --}}
    <section id="process" class="ground-navy grain edge-light section relative overflow-hidden" aria-labelledby="process-heading">
        <div class="page-shell-wide public-process-layout">
            <div class="max-w-[62ch]">
                <p class="eyebrow-rule text-gold" data-reveal="fade">{{ __('How it works') }}</p>
                <h2 id="process-heading" class="public-subheading mt-5 text-balance font-semibold text-white" data-reveal="up">
                    {{ __('Nothing is charged before you approve a written quotation.') }}
                </h2>
            </div>

            <ol class="public-process-steps mt-8 grid gap-6" data-reveal-stagger="90">
                @foreach ([
                    [__('Choose a service or describe your goal'), __('A short request — no documents, no payment.')],
                    [__('Review the initial requirements'), __('See what is needed before you commit to anything.')],
                    [__('Request a written quotation'), __('Professional, statutory and optional costs, listed apart.')],
                    [__('Approve the scope and charges'), __('Approve the written quotation before payment.')],
                    [__('Proceed with the agreed work'), __('Follow the payment and document instructions in your quotation.')],
                    [__('Receive progress updates'), __('Follow up with your consultant using your enquiry reference.')],
                ] as $index => [$title, $body])
                    <li class="relative" data-reveal="up">
                        @if (! $loop->last && $loop->iteration % 3 !== 0)
                            <span aria-hidden="true" class="absolute left-11 top-4.5 hidden h-px w-[calc(100%-1.5rem)] bg-linear-to-r from-white/25 to-white/0 lg:block"></span>
                        @endif

                        <span class="relative flex size-9 items-center justify-center rounded-full border border-white/25 bg-navy-900/60 font-mono text-meta text-teal-300">
                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        </span>

                        <h3 class="mt-5 font-semibold text-white">{{ $title }}</h3>
                        <p class="mt-2 max-w-[34ch] text-meta leading-relaxed text-navy-300">{{ $body }}</p>
                    </li>
                @endforeach
            </ol>
            <x-public.media subject="process" class="public-process-image" />
        </div>
    </section>

    {{-- ── Guidance ────────────────────────────────────────────── --}}
    @if ($articles->isNotEmpty())
        <section class="section" aria-labelledby="guidance-heading">
            <div class="page-shell-wide">
                <div class="flex flex-wrap items-end justify-between gap-6">
                    <div class="max-w-[52ch]">
                        <p class="eyebrow-rule text-teal" data-reveal="fade">{{ __('Reviewed guidance') }}</p>
                        <h2 id="guidance-heading" class="public-subheading mt-5 text-balance font-semibold" data-reveal="up">
                            {{ __('Written by consultants, dated and reviewed.') }}
                        </h2>
                    </div>

                    <x-ui.button variant="ghost" href="{{ route('articles.index') }}" icon-trailing="arrow-right" data-reveal="fade">
                        {{ __('All articles') }}
                    </x-ui.button>
                </div>

                <div class="mt-10 grid gap-4 md:grid-cols-3" data-reveal-stagger="90">
                    @foreach ($articles as $article)
                        <a href="{{ route('articles.show', $article) }}" class="card card-lift public-image-card group flex flex-col no-underline hover:no-underline" data-reveal="up">
                            <x-public.media :subject="$article->slug" class="public-card-image" />
                            @if ($article->category)
                                <p class="card-kicker text-teal">{{ $article->category->name }}</p>
                            @endif
                            <h3 class="card-title mt-3 text-balance text-navy">{{ $article->title }}</h3>
                            <p class="card-body mt-2 flex-1">{{ Str::limit($article->excerpt, 120) }}</p>
                            <span class="mt-6 flex items-center gap-2 border-t border-line-soft pt-4 text-meta text-muted">
                                <x-ui.icon name="clock-counter-clockwise" size="14px" />
                                {{ $article->reviewed_on?->format('j M Y') ?? __('Reviewed before publication') }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ── Close ───────────────────────────────────────────────── --}}
    <section class="section-tight" aria-labelledby="cta-heading">
        <div class="page-shell-wide">
            <div class="ground-navy public-hero public-hero-image public-cta px-8 py-14 lg:px-14 lg:py-16" data-reveal="scale">
                <x-public.media subject="quotation" class="public-hero-backdrop" />

                <div class="public-hero-content max-w-[52ch]">
                    <p class="eyebrow-rule text-gold">{{ __('Not sure what you need?') }}</p>
                    <h2 id="cta-heading" class="public-subheading mt-5 text-balance font-semibold text-white">
                        {{ __('Tell us the outcome you want.') }}
                    </h2>
                    <p class="mt-4 text-navy-300">
                        {{ __('A consultant reviews every request and replies with a written quotation. Recommendations stay preliminary until a person has assessed them.') }}
                    </p>

                    <div class="mt-9 flex flex-wrap gap-3">
                        <x-ui.button href="{{ route('quote.create') }}" icon-trailing="arrow-right">
                            {{ __('Request a Quotation') }}
                        </x-ui.button>
                        <x-ui.button variant="secondary" href="{{ route('page', 'contact') }}" class="on-navy">
                            {{ __('Contact us') }}
                        </x-ui.button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts::public>
