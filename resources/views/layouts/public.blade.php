@props([
    'title' => null,
    'metaDescription' => null,
    'canonical' => null,
    'indexable' => true,
    'schema' => null,
])

@inject('chrome', 'App\Support\SiteChrome')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', ['title' => $title])

    @if ($metaDescription)
        <meta name="description" content="{{ $metaDescription }}">
    @endif

    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">

    @unless ($indexable)
        <meta name="robots" content="noindex, follow">
    @endunless

    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:title" content="{{ $title ?? config('app.name') }}">
    @if ($metaDescription)
        <meta property="og:description" content="{{ $metaDescription }}">
    @endif
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    @if ($schema)
        <script type="application/ld+json">@json($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
    @endif
</head>
<body>
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:z-[60] focus:m-3 focus:rounded-control focus:bg-white focus:px-4 focus:py-2">
        {{ __('Skip to content') }}
    </a>

    {{-- ── Utility bar ─────────────────────────────────────────────── --}}
    <div class="bg-navy-900 text-navy-300">
        <div class="page-shell-wide flex flex-wrap items-center gap-x-6 gap-y-1 py-2.5 text-meta">
            @if ($chrome->has('contact.phone'))
                <span class="inline-flex items-center gap-1.5">
                    <x-ui.icon name="phone" size="14px" />{{ $chrome->setting('contact.phone') }}
                </span>
            @endif
            @if ($chrome->has('contact.hours'))
                <span class="hidden items-center gap-1.5 sm:inline-flex">
                    <x-ui.icon name="clock" size="14px" />{{ $chrome->setting('contact.hours') }}
                </span>
            @endif
            <a href="{{ route('track') }}" class="ms-auto inline-flex items-center gap-1.5 text-white hover:text-white">
                <x-ui.icon name="magnifying-glass" size="14px" />{{ __('Track Application') }}
            </a>
        </div>
    </div>

    {{-- ── Header ──────────────────────────────────────────────────── --}}
    <header class="site-header border-b border-transparent">
        <div class="page-shell-wide flex items-center gap-6 py-3.5">
            <a href="{{ route('home') }}" class="flex flex-none items-center gap-3 no-underline hover:no-underline">
                <img src="{{ asset('images/itm-logo.png') }}" alt="" class="size-10 rounded-control object-cover">
                <span class="leading-tight">
                    <span class="block font-semibold tracking-tight text-navy">{{ config('app.name') }}</span>
                    @if ($chrome->has('site.tagline'))
                        <span class="hidden text-[11px] text-muted sm:block">{{ $chrome->setting('site.tagline') }}</span>
                    @endif
                </span>
            </a>

            <nav aria-label="{{ __('Primary') }}" class="hidden min-w-0 flex-1 items-center justify-center gap-7 text-meta lg:flex">
                <a href="{{ route('services.index') }}" @class(['whitespace-nowrap no-underline hover:no-underline', 'text-teal' => request()->routeIs('services.*'), 'text-navy hover:text-teal' => ! request()->routeIs('services.*')])>{{ __('Services') }}</a>
                <a href="{{ route('packages') }}" @class(['whitespace-nowrap no-underline hover:no-underline', 'text-teal' => request()->routeIs('packages'), 'text-navy hover:text-teal' => ! request()->routeIs('packages')])>{{ __('Packages') }}</a>
                <a href="{{ route('page', 'requirements') }}" class="whitespace-nowrap text-navy no-underline hover:text-teal hover:no-underline">{{ __('Requirements') }}</a>
                <a href="{{ route('articles.index') }}" @class(['whitespace-nowrap no-underline hover:no-underline', 'text-teal' => request()->routeIs('articles.*'), 'text-navy hover:text-teal' => ! request()->routeIs('articles.*')])>{{ __('Resources') }}</a>
                <a href="{{ route('page', 'about') }}" class="whitespace-nowrap text-navy no-underline hover:text-teal hover:no-underline">{{ __('About') }}</a>
            </nav>

            <div class="ms-auto flex flex-none items-center gap-2 lg:ms-0">
                @auth
                    <x-ui.button variant="quiet" href="{{ route('dashboard') }}" class="hidden sm:inline-flex">
                        {{ __('My workspace') }}
                    </x-ui.button>
                @else
                    <x-ui.button variant="quiet" href="{{ route('login') }}" class="hidden xl:inline-flex">
                        {{ __('Sign in') }}
                    </x-ui.button>
                @endauth
                <x-ui.button href="{{ route('quote.create') }}" icon-trailing="arrow-right" class="whitespace-nowrap">
                    {{ __('Start Your Application') }}
                </x-ui.button>
            </div>
        </div>

        <div class="scroll-rail h-px w-full" aria-hidden="true"></div>
    </header>

    <main id="main">
        {{ $slot }}
    </main>

    {{-- ── Footer ──────────────────────────────────────────────────── --}}
    <footer class="bg-navy-900 pb-10 pt-16 text-navy-300">
        <div class="page-shell-wide">
            <div class="flex flex-wrap items-start justify-between gap-10 border-b border-white/10 pb-10">
                <div class="max-w-[34ch]">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/itm-logo.png') }}" alt="" class="size-10 rounded-control object-cover">
                        <span class="font-semibold text-white">{{ config('app.name') }}</span>
                    </div>
                    <p class="mt-4 text-meta leading-relaxed">
                        {{ __('Registration and compliance support for Zimbabwean businesses, trusts and organisations.') }}
                    </p>
                    <dl class="mt-5 flex flex-col gap-1.5 text-meta">
                        @if ($chrome->has('contact.email'))
                            <div class="flex gap-2">
                                <dt class="sr-only">{{ __('Email') }}</dt>
                                <dd><a href="mailto:{{ $chrome->setting('contact.email') }}" class="text-navy-300 hover:text-white">{{ $chrome->setting('contact.email') }}</a></dd>
                            </div>
                        @endif
                        @if ($chrome->has('contact.address'))
                            <div class="flex gap-2">
                                <dt class="sr-only">{{ __('Address') }}</dt>
                                <dd>{{ $chrome->setting('contact.address') }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                <div class="flex flex-wrap gap-x-16 gap-y-8">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-white">{{ __('Services') }}</p>
                        <ul class="mt-4 flex flex-col gap-2.5 text-meta">
                            @foreach ($chrome->categories() as $category)
                                <li>
                                    <a href="{{ route('services.category', $category) }}" class="text-navy-300 no-underline hover:text-white">{{ $category->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-white">{{ __('Company') }}</p>
                        <ul class="mt-4 flex flex-col gap-2.5 text-meta">
                            @foreach ([
                                'About' => ['page', 'about'],
                                'Contact' => ['page', 'contact'],
                                'Resources' => ['articles.index', null],
                                'Requirements' => ['page', 'requirements'],
                                'Track Application' => ['track', null],
                            ] as $label => [$routeName, $param])
                                <li>
                                    <a href="{{ $param ? route($routeName, $param) : route($routeName) }}" class="text-navy-300 no-underline hover:text-white">{{ __($label) }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-white">{{ __('Legal') }}</p>
                        <ul class="mt-4 flex flex-col gap-2.5 text-meta">
                            @foreach (['Privacy Policy' => 'privacy', 'Terms of Service' => 'terms', 'Document Handling' => 'document-handling'] as $label => $slug)
                                <li>
                                    <a href="{{ route('page', $slug) }}" class="text-navy-300 no-underline hover:text-white">{{ __($label) }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-4 pt-8 text-[11px]">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. {{ $chrome->setting('site.tagline') }}</p>
                @if ($chrome->has('site.safety_note'))
                    <p class="max-w-[52ch]">{{ $chrome->setting('site.safety_note') }}</p>
                @endif
            </div>
        </div>
    </footer>
</body>
</html>
