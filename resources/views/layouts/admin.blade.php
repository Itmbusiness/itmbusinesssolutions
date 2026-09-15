@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', ['title' => $title ? $title.' · Admin' : 'Admin'])
    <meta name="robots" content="noindex, nofollow">
</head>
<body class="min-h-screen bg-page">
    <div class="flex min-h-screen flex-col lg:flex-row">
        {{-- ── Sidebar ─────────────────────────────────────────── --}}
        <aside class="ground-navy flex w-full flex-none flex-col lg:min-h-screen lg:w-64">
            <div class="flex items-center gap-3 px-5 py-4">
                <img src="{{ asset('images/itm-logo.png') }}" alt="" class="size-9 rounded-control object-cover">
                <span class="leading-tight">
                    <span class="block text-meta font-semibold text-white">{{ config('app.name') }}</span>
                    <span class="block text-[11px] text-navy-300">{{ __('Admin') }}</span>
                </span>
            </div>

            <nav aria-label="{{ __('Admin') }}" class="flex-1 px-3 pb-4">
                @foreach ([
                    ['admin.dashboard', 'gauge', __('Overview'), null],
                    ['admin.leads.index', 'tray', __('Leads'), 'admin.leads.*'],
                    ['admin.services.index', 'briefcase', __('Services'), 'admin.services.*'],
                    ['admin.categories.index', 'folders', __('Categories'), 'admin.categories.*'],
                    ['admin.packages.index', 'package', __('Packages'), 'admin.packages.*'],
                    ['admin.articles.index', 'article', __('Articles'), 'admin.articles.*'],
                    ['admin.faqs.index', 'question', __('FAQs'), 'admin.faqs.*'],
                    ['admin.pages.index', 'file-text', __('Pages'), 'admin.pages.*'],
                    ['admin.users.index', 'users', __('Users'), 'admin.users.*'],
                    ['admin.settings.edit', 'gear', __('Settings'), 'admin.settings.*'],
                ] as [$route, $icon, $label, $pattern])
                    @php $active = $pattern ? request()->routeIs($pattern) : request()->routeIs($route); @endphp
                    <a href="{{ route($route) }}"
                        @class([
                            'mt-0.5 flex items-center gap-3 rounded-control px-3 py-2 text-meta no-underline hover:no-underline transition-colors',
                            'bg-white/10 font-semibold text-white' => $active,
                            'text-navy-300 hover:bg-white/5 hover:text-white' => ! $active,
                        ])
                        @if ($active) aria-current="page" @endif
                    >
                        <x-ui.icon :name="$icon" size="17px" />
                        {{ $label }}
                    </a>
                @endforeach
            </nav>

            <div class="border-t border-white/10 px-3 py-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-control px-3 py-2 text-meta text-navy-300 no-underline hover:bg-white/5 hover:text-white hover:no-underline">
                    <x-ui.icon name="arrow-left" size="17px" />{{ __('Back to website') }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-control px-3 py-2 text-meta text-navy-300 hover:bg-white/5 hover:text-white">
                        <x-ui.icon name="sign-out" size="17px" />{{ __('Log out') }}
                    </button>
                </form>
            </div>
        </aside>

        {{-- ── Content ─────────────────────────────────────────── --}}
        <div class="min-w-0 flex-1">
            <div class="page-shell-wide py-8">
                @if (session('status'))
                    <x-ui.alert variant="success" class="mb-6" :title="session('status')" />
                @endif

                @if ($errors->any())
                    <x-ui.alert variant="problem" class="mb-6" :title="__('Please check the form')">
                        <ul class="mt-1 list-disc ps-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-ui.alert>
                @endif

                {{ $slot }}
            </div>
        </div>
    </div>

    @fluxScripts
</body>
</html>
