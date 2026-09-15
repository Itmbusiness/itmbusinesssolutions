<x-layouts::public :title="__('Request received')" :indexable="false">
    <x-public.page-header
        :eyebrow="__('Request received')"
        :heading="__('Thank you — your request is with a consultant.')"
        :lead="__('A consultant reviews every request personally. You will hear from us on your preferred channel.')"
    />

    <div class="page-shell section-tight">
        @if ($reference)
            <x-ui.card class="elev-md">
                <p class="card-kicker text-teal">{{ __('Your reference') }}</p>
                <p class="mt-2 font-mono text-h2 tracking-tight text-navy">{{ $reference }}</p>
                <p class="card-body mt-3">
                    {{ __('Keep this reference. You can use it with your email address to check progress at any time.') }}
                </p>
                <x-ui.button variant="secondary" href="{{ route('track') }}" class="mt-5">
                    {{ __('Track this request') }}
                </x-ui.button>
            </x-ui.card>
        @else
            <x-ui.alert variant="info" :title="__('Your request was sent')">
                {{ __('A consultant will be in touch shortly.') }}
            </x-ui.alert>
        @endif

        <section class="mt-10">
            <h2 class="text-h3 font-semibold tracking-tight">{{ __('What happens next') }}</h2>
            <ol class="mt-5 flex flex-col gap-5">
                @foreach ([
                    __('A consultant reviews your request and checks what is actually needed.'),
                    __('If anything is unclear, we ask you — we never guess at scope.'),
                    __('You receive a written quotation separating professional, statutory and optional charges.'),
                    __('Only after you approve it is payment requested.'),
                ] as $index => $step)
                    <li class="flex gap-4">
                        <span class="flex size-8 flex-none items-center justify-center rounded-full border border-line bg-white font-mono text-meta font-semibold text-teal">
                            {{ $index + 1 }}
                        </span>
                        <span class="pt-1 text-ink">{{ $step }}</span>
                    </li>
                @endforeach
            </ol>
        </section>

        <x-ui.alert variant="info" class="mt-10" :title="__('Staying safe')">
            <x-public.safety-note class="text-meta" />
        </x-ui.alert>

        <div class="mt-10 flex flex-wrap gap-3">
            <x-ui.button variant="secondary" href="{{ route('services.index') }}">{{ __('Browse services') }}</x-ui.button>
            <x-ui.button variant="quiet" href="{{ route('articles.index') }}">{{ __('Read guidance') }}</x-ui.button>
        </div>
    </div>
</x-layouts::public>
