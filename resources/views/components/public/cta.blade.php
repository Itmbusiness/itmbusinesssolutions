@props([
    'heading' => null,
    'body' => null,
    'service' => null,
])

<section class="section-tight" aria-labelledby="cta-heading">
    <div class="page-shell-wide">
        <div class="ground-navy grain relative overflow-hidden rounded-card px-8 py-14 lg:px-14 lg:py-16" data-reveal="scale">
            <div aria-hidden="true" class="pointer-events-none absolute inset-0 -z-10">
                <div class="orb-drift absolute -bottom-28 right-0 size-96 rounded-full bg-teal/20 blur-[110px]"></div>
            </div>

            <div class="max-w-[52ch]">
                <p class="eyebrow-rule text-gold">{{ __('Ready to begin?') }}</p>
                <h2 id="cta-heading" class="mt-5 text-balance text-[clamp(1.7rem,3.2vw,2.4rem)] font-semibold leading-[1.12] tracking-tight text-white">
                    {{ $heading ?? __('Tell us about your organisation and desired outcome.') }}
                </h2>
                <p class="mt-4 text-navy-300">
                    {{ $body ?? __('We assess the request and send a written quotation before asking for payment.') }}
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <x-ui.button
                        href="{{ $service ? route('quote.create', ['service' => $service->slug]) : route('quote.create') }}"
                        icon-trailing="arrow-right"
                    >
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
