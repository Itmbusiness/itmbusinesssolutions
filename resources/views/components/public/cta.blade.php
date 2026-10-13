@props([
    'heading' => null,
    'body' => null,
    'service' => null,
])

<section class="section-tight" aria-labelledby="cta-heading">
    <div class="page-shell-wide">
        <div class="ground-navy public-hero public-hero-image public-cta px-8 py-14 lg:px-14 lg:py-16" data-reveal="scale">
            <x-public.media subject="quotation" class="public-hero-backdrop" />

            <div class="public-hero-content max-w-[52ch]">
                <p class="eyebrow-rule text-gold">{{ __('Ready to begin?') }}</p>
                <h2 id="cta-heading" class="public-subheading mt-5 text-balance font-semibold text-white">
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
