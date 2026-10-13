@props([
    'categories',
])

{{--
    The header's "Services" nav item is a split control: the label is a real
    link to /services (works with JS disabled or on a first tap on touch),
    and the caret next to it opens a mega menu of every category and a few
    of its services. `data-menu-trigger`/`data-menu-panel` are wired up by
    `initMenus()` in motion.js, the same generic open/close-on-outside-click
    logic the header's mobile nav and avatar menu use.

    The panel is positioned against the header's own shell (the nearest
    ancestor with `position: relative`), not against this trigger — "Services"
    rarely sits at the horizontal centre of the header, so centring the panel
    on the trigger itself pushed it past the edge of the viewport. Anchoring
    to the full-width shell and centring within it keeps the panel on-screen
    at every width the mega menu is shown at.
--}}
<div class="flex items-center">
    <a
        href="{{ route('services.index') }}"
        @class(['whitespace-nowrap no-underline hover:no-underline', 'text-teal' => request()->routeIs('services.*'), 'text-navy hover:text-teal' => ! request()->routeIs('services.*')])
    >
        {{ __('Services') }}
    </a>

    <button
        type="button"
        data-menu-trigger
        aria-controls="services-mega-panel"
        aria-expanded="false"
        class="-me-1 ms-1 flex size-6 flex-none items-center justify-center rounded-control text-navy hover:text-teal"
    >
        <span class="sr-only">{{ __('Browse service categories') }}</span>
        <x-ui.icon name="caret-down" size="12px" class="transition-transform duration-200" />
    </button>

    <div
        id="services-mega-panel"
        data-menu-panel
        hidden
        class="absolute inset-x-0 top-full z-50 mx-auto mt-4 w-[min(920px,100%)] rounded-card border border-line bg-white p-6 shadow-lg"
    >
        <div class="grid gap-x-6 gap-y-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($categories as $category)
                <div>
                    <a href="{{ route('services.category', $category) }}" class="flex items-center gap-2.5 no-underline hover:no-underline">
                        <span class="flex size-8 flex-none items-center justify-center rounded-control bg-teal-100 text-teal">
                            <x-ui.icon :name="$category->icon ?? 'folder'" size="16px" />
                        </span>
                        <span class="font-semibold text-navy">{{ $category->name }}</span>
                    </a>

                    @if ($category->services->isNotEmpty())
                        <ul class="mt-3 flex flex-col gap-2">
                            @foreach ($category->services as $service)
                                <li>
                                    <a href="{{ route('services.show', $service) }}" class="text-meta text-muted no-underline hover:text-teal hover:no-underline">
                                        {{ $service->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if ($category->services_count > $category->services->count())
                        <a href="{{ route('services.category', $category) }}" class="mt-2 inline-flex items-center gap-1 text-meta font-semibold text-teal no-underline hover:no-underline">
                            {{ __('View all') }}
                            <x-ui.icon name="arrow-right" size="12px" />
                        </a>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex flex-wrap items-center justify-between gap-4 border-t border-line-soft pt-5">
            <p class="text-meta text-muted">{{ __("Not sure which service you need? We'll assess it before you request a quotation.") }}</p>
            <x-ui.button variant="secondary" href="{{ route('quote.create') }}" icon-trailing="arrow-right" class="flex-none">
                {{ __('Get a Recommendation') }}
            </x-ui.button>
        </div>
    </div>
</div>
