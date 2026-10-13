{{--
    Authenticated state of the header's account slot: a default initials
    avatar (no upload flow exists yet, so this is deliberately a generic
    badge rather than an invented photo) opening a menu into the workspace,
    account settings and log out — the profile itself is edited from
    Settings inside the dashboard, not from here.
--}}
<div {{ $attributes->class('relative flex items-center') }}>
    <button
        type="button"
        data-menu-trigger
        aria-controls="account-menu-panel"
        aria-expanded="false"
        class="flex size-10 flex-none items-center justify-center rounded-full bg-teal-100 font-semibold text-teal hover:bg-teal-200"
    >
        <span class="sr-only">{{ __('Your account') }}</span>
        {{ auth()->user()->initials() }}
    </button>

    <div
        id="account-menu-panel"
        data-menu-panel
        hidden
        class="absolute right-0 top-full z-50 mt-3 w-64 rounded-card border border-line bg-white p-2 shadow-lg"
    >
        <div class="flex items-center gap-3 px-3 py-2.5">
            <span class="flex size-9 flex-none items-center justify-center rounded-full bg-teal-100 text-meta font-semibold text-teal">
                {{ auth()->user()->initials() }}
            </span>
            <span class="min-w-0">
                <span class="block truncate font-semibold text-navy">{{ auth()->user()->name }}</span>
                <span class="block truncate text-meta text-muted">{{ auth()->user()->email }}</span>
            </span>
        </div>

        <div class="my-1 border-t border-line-soft"></div>

        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 rounded-control px-3 py-2 text-meta font-medium text-ink no-underline hover:bg-page hover:no-underline">
            <x-ui.icon name="squares-four" size="16px" class="flex-none text-muted" />
            {{ __('My workspace') }}
        </a>

        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 rounded-control px-3 py-2 text-meta font-medium text-ink no-underline hover:bg-page hover:no-underline">
            <x-ui.icon name="gear-six" size="16px" class="flex-none text-muted" />
            {{ __('Settings') }}
        </a>

        @if (auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 rounded-control px-3 py-2 text-meta font-medium text-ink no-underline hover:bg-page hover:no-underline">
                <x-ui.icon name="shield-check" size="16px" class="flex-none text-muted" />
                {{ __('Site admin') }}
            </a>
        @endif

        <div class="my-1 border-t border-line-soft"></div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex w-full items-center gap-2.5 rounded-control px-3 py-2 text-start text-meta font-medium text-problem hover:bg-problem-tint">
                <x-ui.icon name="sign-out" size="16px" class="flex-none" />
                {{ __('Log out') }}
            </button>
        </form>
    </div>
</div>
