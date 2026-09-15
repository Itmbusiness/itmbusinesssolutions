<x-layouts::public
    :title="$page?->seo_title ?? __('Packages')"
    :meta-description="$page?->meta_description"
>
    <x-public.page-header
        :eyebrow="__('Packages')"
        :heading="$page?->title ?? __('Practical packages built around your goal')"
        :lead="__('Packages reduce duplicate requirements while keeping every professional, statutory and optional charge visible.')"
    />

    <div class="page-shell-wide section-tight">
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3" data-reveal-stagger="80">
            @foreach ($packages as $package)
                <x-ui.card class="card-lift flex flex-col" data-reveal="up">
                    <h2 class="text-h3 font-semibold tracking-tight">{{ $package->name }}</h2>
                    <p class="card-body mt-2 flex-1">{{ $package->summary }}</p>

                    @if (filled($package->includes))
                        <ul class="mt-4 flex flex-col gap-2">
                            @foreach ($package->includes as $item)
                                <li class="flex gap-2.5 text-meta text-ink">
                                    <x-ui.icon name="check" size="15px" class="mt-0.5 flex-none text-teal" />
                                    <span>{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <x-ui.button variant="secondary" href="{{ route('quote.create') }}" class="btn-block mt-5">
                        {{ __('Request a Package Quotation') }}
                    </x-ui.button>
                </x-ui.card>
            @endforeach
        </div>

        <x-ui.card class="mt-10">
            <h2 class="text-h3 font-semibold tracking-tight">{{ __('Your quotation will show') }}</h2>
            <ul class="mt-4 grid gap-2.5 sm:grid-cols-2">
                @foreach ([
                    __('Professional service fees'),
                    __('Statutory or authority charges'),
                    __('Optional third-party costs'),
                    __('Assumptions and exclusions'),
                    __('Payment schedule'),
                    __('Quote validity and estimated processing range'),
                ] as $item)
                    <li class="flex gap-2.5 text-ink">
                        <x-ui.icon name="check-circle" weight="fill" size="17px" class="mt-0.5 flex-none text-teal" />
                        <span>{{ $item }}</span>
                    </li>
                @endforeach
            </ul>
        </x-ui.card>
    </div>

    <x-public.cta />
</x-layouts::public>
