@props([
    'categories',
])

{{--
    Below `lg:` the primary nav and the "Services" mega menu are both
    hidden — this is the only way to reach them on a phone, so the drawer
    carries the full nav, every service category, and the auth actions the
    desktop header spreads across two areas. It renders its own top bar
    rather than trying to sit under the real header, so its offset never
    depends on measuring the sticky header's height.
--}}
<button
    type="button"
    data-menu-trigger
    aria-controls="mobile-nav-panel"
    aria-expanded="false"
    class="flex size-10 flex-none items-center justify-center rounded-control text-navy lg:hidden"
>
    <span class="sr-only">{{ __('Open menu') }}</span>
    <x-ui.icon name="list" size="22px" />
</button>

<div
    id="mobile-nav-panel"
    data-menu-panel
    data-lock-scroll
    hidden
    class="fixed inset-0 z-60 overflow-y-auto bg-white lg:hidden"
>
    <div class="flex items-center justify-between border-b border-line px-4 py-3.5">
        <a href="{{ route('home') }}" class="flex items-center gap-3 no-underline hover:no-underline">
            <img src="{{ asset('images/itm-logo.png') }}" alt="" class="size-10 rounded-control object-cover">
            <span class="font-semibold text-navy">{{ config('app.name') }}</span>
        </a>

        <button type="button" data-menu-trigger aria-controls="mobile-nav-panel" aria-expanded="true" class="flex size-10 flex-none items-center justify-center rounded-control text-navy">
            <span class="sr-only">{{ __('Close menu') }}</span>
            <x-ui.icon name="x" size="22px" />
        </button>
    </div>

    <nav aria-label="{{ __('Primary') }}" class="page-shell flex flex-col gap-1 py-6">
        <a href="{{ route('services.index') }}" class="rounded-control px-3 py-3 font-semibold text-navy no-underline hover:bg-page hover:no-underline">
            {{ __('Services') }}
        </a>

        <div class="ms-2 flex flex-col gap-0.5 border-s border-line-soft ps-3">
            @foreach ($categories as $category)
                <a href="{{ route('services.category', $category) }}" class="rounded-control px-3 py-2 text-meta text-muted no-underline hover:bg-page hover:text-teal hover:no-underline">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        <a href="{{ route('packages') }}" class="mt-2 rounded-control px-3 py-3 font-semibold text-navy no-underline hover:bg-page hover:no-underline">
            {{ __('Packages') }}
        </a>
        <a href="{{ route('page', 'requirements') }}" class="rounded-control px-3 py-3 font-semibold text-navy no-underline hover:bg-page hover:no-underline">
            {{ __('Requirements') }}
        </a>
        <a href="{{ route('articles.index') }}" class="rounded-control px-3 py-3 font-semibold text-navy no-underline hover:bg-page hover:no-underline">
            {{ __('Resources') }}
        </a>
        <a href="{{ route('page', 'about') }}" class="rounded-control px-3 py-3 font-semibold text-navy no-underline hover:bg-page hover:no-underline">
            {{ __('About') }}
        </a>

        <div class="my-3 border-t border-line-soft"></div>

        @auth
            <a href="{{ route('dashboard') }}" class="rounded-control px-3 py-3 font-semibold text-navy no-underline hover:bg-page hover:no-underline">
                {{ __('My workspace') }}
            </a>
            <a href="{{ route('profile.edit') }}" class="rounded-control px-3 py-3 font-semibold text-navy no-underline hover:bg-page hover:no-underline">
                {{ __('Settings') }}
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full rounded-control px-3 py-3 text-start font-semibold text-problem hover:bg-problem-tint">
                    {{ __('Log out') }}
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="rounded-control px-3 py-3 font-semibold text-navy no-underline hover:bg-page hover:no-underline">
                {{ __('Sign in') }}
            </a>
            <a href="{{ route('register') }}" class="rounded-control px-3 py-3 font-semibold text-teal no-underline hover:bg-page hover:no-underline">
                {{ __('Register') }}
            </a>
        @endauth

        <x-ui.button href="{{ route('quote.create') }}" icon-trailing="arrow-right" class="btn-block mt-4">
            {{ __('Start Your Application') }}
        </x-ui.button>
    </nav>
</div>
