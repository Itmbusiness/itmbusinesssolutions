<x-layouts::admin :title="__('FAQs')">
    <x-admin.header :title="__('FAQs')">
        <x-ui.button href="{{ route('admin.faqs.create') }}" icon="plus">{{ __('New FAQ') }}</x-ui.button>
    </x-admin.header>

    <section class="mt-6" aria-labelledby="global-faqs">
        <h2 id="global-faqs" class="text-h3">{{ __('Site-wide') }}</h2>
        <p class="card-body mt-1">{{ __('Shown on the public FAQ page.') }}</p>

        @if ($globalFaqs->isEmpty())
            <x-ui.empty-state class="mt-4" icon="question" :title="__('No site-wide FAQs yet')">
                {{ __('Add one to have it appear on the FAQ page.') }}
            </x-ui.empty-state>
        @else
            <div class="mt-4 flex flex-col gap-2">
                @foreach ($globalFaqs as $faq)
                    <x-ui.card class="flex-row items-start justify-between gap-4">
                        <div>
                            <p class="font-semibold text-navy">{{ $faq->question }}</p>
                            <p class="card-body mt-1">{{ Str::limit($faq->answer, 140) }}</p>
                        </div>
                        <div class="flex flex-none items-center gap-2">
                            @unless ($faq->is_published)
                                <x-ui.badge variant="neutral">{{ __('Draft') }}</x-ui.badge>
                            @endunless
                            <a href="{{ route('admin.faqs.edit', $faq) }}" class="text-meta font-semibold text-teal">{{ __('Edit') }}</a>
                        </div>
                    </x-ui.card>
                @endforeach
            </div>
        @endif
    </section>

    <section class="mt-10" aria-labelledby="service-faqs">
        <h2 id="service-faqs" class="text-h3">{{ __('On service pages') }}</h2>

        @foreach ($serviceFaqs as $serviceName => $faqs)
            <div class="mt-5">
                <p class="card-kicker text-teal">{{ $serviceName }}</p>
                <div class="mt-2 flex flex-col gap-2">
                    @foreach ($faqs as $faq)
                        <x-ui.card class="flex-row items-start justify-between gap-4">
                            <div>
                                <p class="font-semibold text-navy">{{ $faq->question }}</p>
                                <p class="card-body mt-1">{{ Str::limit($faq->answer, 140) }}</p>
                            </div>
                            <a href="{{ route('admin.faqs.edit', $faq) }}" class="flex-none text-meta font-semibold text-teal">{{ __('Edit') }}</a>
                        </x-ui.card>
                    @endforeach
                </div>
            </div>
        @endforeach
    </section>
</x-layouts::admin>
