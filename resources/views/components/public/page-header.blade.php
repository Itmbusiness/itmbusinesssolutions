@props([
    'eyebrow' => null,
    'heading',
    'lead' => null,
    'breadcrumbs' => [],
])

{{-- The navy band every inner page opens with — §01's "authority" ground. --}}
<section class="ground-navy grain edge-light relative overflow-hidden">
    <div class="page-shell-wide py-14 lg:py-20">
        @if (! empty($breadcrumbs))
            <nav aria-label="{{ __('Breadcrumb') }}" class="mb-6">
                <ol class="flex flex-wrap items-center gap-2 text-meta text-navy-300">
                    @foreach ($breadcrumbs as $label => $url)
                        <li class="flex items-center gap-2">
                            @if ($url)
                                <a href="{{ $url }}" class="text-navy-300 no-underline hover:text-white">{{ $label }}</a>
                                <x-ui.icon name="caret-right" size="12px" />
                            @else
                                <span class="text-white">{{ $label }}</span>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        @endif

        @if ($eyebrow)
            <p class="eyebrow-rule text-teal-300">{{ $eyebrow }}</p>
        @endif

        <h1 class="mt-5 max-w-[24ch] text-balance text-[clamp(2rem,4.4vw,3.2rem)] font-semibold leading-[1.08] tracking-[-0.025em] text-white">
            {{ $heading }}
        </h1>

        @if ($lead)
            <p class="mt-5 max-w-[60ch] text-pretty text-lead text-navy-300">{{ $lead }}</p>
        @endif

        {{ $slot }}
    </div>
</section>
