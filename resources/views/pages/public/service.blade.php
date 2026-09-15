<x-layouts::public
    :title="$service->seo_title ?? $service->name"
    :meta-description="$service->meta_description"
    :canonical="route('services.show', $service)"
    :schema="$schema"
>
    <x-public.page-header
        :eyebrow="$service->category->name"
        :heading="$service->name"
        :lead="$service->intro"
        :breadcrumbs="[
            __('Services') => route('services.index'),
            $service->category->name => route('services.category', $service->category),
            $service->name => null,
        ]"
    >
        <div class="mt-9 flex flex-wrap items-center gap-3">
            <x-ui.button href="{{ route('quote.create', ['service' => $service->slug]) }}" icon-trailing="arrow-right">
                {{ __('Request a Written Quotation') }}
            </x-ui.button>
            <x-ui.button variant="secondary" icon="whatsapp-logo" href="{{ route('quote.create', ['service' => $service->slug]) }}" class="on-navy">
                {{ __('Ask on WhatsApp') }}
            </x-ui.button>
        </div>
    </x-public.page-header>

    <div class="page-shell-wide section-tight">
        <div class="grid gap-12 lg:grid-cols-[minmax(0,1fr)_320px]">
            {{-- ── Main column ─────────────────────────────────── --}}
            <div class="min-w-0">
                @if ($service->isStale())
                    <x-ui.alert variant="attention" title="{{ __('This page is due for review') }}" class="mb-8">
                        {{ __('Requirements and official processes change. A consultant will confirm the current position in your written quotation.') }}
                    </x-ui.alert>
                @endif

                @if (filled($service->suited_to))
                    <section class="mb-10" aria-labelledby="suited-to">
                        <h2 id="suited-to" class="text-h3 font-semibold tracking-tight">{{ __('Is this service right for you?') }}</h2>
                        <ul class="mt-4 flex flex-col gap-2.5">
                            @foreach ($service->suited_to as $item)
                                <li class="flex gap-3 text-ink">
                                    <x-ui.icon name="check-circle" weight="fill" size="18px" class="mt-0.5 flex-none text-teal" />
                                    <span>{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <p class="field-hint mt-4">
                            {{ __('If the correct route depends on your circumstances, ITM will assess the information before asking you to approve a quotation.') }}
                        </p>
                    </section>
                @endif

                @if (filled($service->includes))
                    <section class="mb-10" aria-labelledby="includes">
                        <h2 id="includes" class="text-h3 font-semibold tracking-tight">{{ __('What the service can include') }}</h2>
                        <ul class="mt-4 flex flex-col gap-2.5">
                            @foreach ($service->includes as $item)
                                <li class="flex gap-3 text-ink">
                                    <x-ui.icon name="dot-outline" weight="fill" size="18px" class="mt-0.5 flex-none text-teal" />
                                    <span>{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <p class="field-hint mt-4">
                            {{ __('The final deliverables are the items written in the approved quotation. Authority decisions and items outside the quotation are excluded.') }}
                        </p>
                    </section>
                @endif

                @if (filled($service->requirements))
                    <section class="mb-10" aria-labelledby="requirements">
                        <h2 id="requirements" class="text-h3 font-semibold tracking-tight">{{ __('Starting requirements') }}</h2>
                        <x-ui.card class="mt-4">
                            <ul class="flex flex-col gap-3">
                                @foreach ($service->requirements as $item)
                                    <li class="flex gap-3 text-ink">
                                        <span class="mt-0.5 flex size-5 flex-none items-center justify-center rounded-[4px] border border-line bg-page"></span>
                                        <span>{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </x-ui.card>
                        <p class="field-hint mt-4">
                            {{ __('This is a preparation checklist. A consultant may request fewer or additional items after reviewing your organisation and the current official process.') }}
                        </p>
                    </section>
                @endif

                @if (filled($service->steps))
                    <section class="mb-10" aria-labelledby="how-it-works">
                        <h2 id="how-it-works" class="text-h3 font-semibold tracking-tight">{{ __('How it works') }}</h2>
                        <ol class="mt-5 flex flex-col gap-5">
                            @foreach ($service->steps as $index => $step)
                                <li class="flex gap-4">
                                    <span class="flex size-8 flex-none items-center justify-center rounded-full border border-line bg-white font-mono text-meta font-semibold text-teal">
                                        {{ $index + 1 }}
                                    </span>
                                    <span class="pt-1 text-ink">{{ $step }}</span>
                                </li>
                            @endforeach
                        </ol>
                    </section>
                @endif

                {{-- Charges: the board is explicit that fee types stay separate
                     and that no amount is ever invented. --}}
                <section class="mb-10" aria-labelledby="charges">
                    <h2 id="charges" class="text-h3 font-semibold tracking-tight">{{ __('Charges and payment') }}</h2>
                    <x-ui.table class="mt-4" :caption="__('Charge types')">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Charge type') }}</th>
                                <th scope="col">{{ __('Amount') }}</th>
                                <th scope="col">{{ __('Notes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ([
                                [__('Professional fee'), __('Quoted after assessment'), __('Work performed by ITM')],
                                [__('Statutory / authority fee'), __('Confirmed at quotation'), __('Paid to or for the responsible authority')],
                                [__('Optional costs'), __('If selected'), __('Courier, certified copies, expedited handling')],
                            ] as [$type, $amount, $note])
                                <tr>
                                    <th scope="row" class="font-normal">{{ $type }}</th>
                                    <td class="text-muted">{{ $amount }}</td>
                                    <td class="text-muted">{{ $note }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </x-ui.table>
                    <p class="field-hint mt-4">
                        {{ __('Payment is requested only after you approve the current quotation version.') }}
                    </p>
                </section>

                @if ($service->faqs->isNotEmpty())
                    <section class="mb-10" aria-labelledby="faqs">
                        <h2 id="faqs" class="text-h3 font-semibold tracking-tight">{{ __('Frequently asked questions') }}</h2>
                        <div class="mt-4 flex flex-col gap-2">
                            @foreach ($service->faqs as $faq)
                                <details class="card group">
                                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-navy">
                                        {{ $faq->question }}
                                        <x-ui.icon name="plus" size="16px" class="flex-none text-teal transition-transform group-open:rotate-45" />
                                    </summary>
                                    <p class="card-body mt-3">{{ $faq->answer }}</p>
                                </details>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            {{-- ── Sidebar ─────────────────────────────────────── --}}
            <aside class="flex flex-col gap-4 lg:sticky lg:top-24 lg:self-start">
                <x-ui.card class="elev-md">
                    <p class="card-kicker text-teal">{{ __('Next step') }}</p>
                    <h2 class="card-title mt-2">{{ __('Request a written quotation') }}</h2>
                    <p class="card-body mt-2">
                        {{ __('No documents and no payment to start. A consultant reviews your request and confirms what is actually needed.') }}
                    </p>
                    <x-ui.button href="{{ route('quote.create', ['service' => $service->slug]) }}" class="btn-block mt-4" icon-trailing="arrow-right">
                        {{ __('Request a Quotation') }}
                    </x-ui.button>
                </x-ui.card>

                @if ($service->relatedServices->isNotEmpty())
                    <x-ui.card>
                        <p class="card-kicker">{{ __('Related services') }}</p>
                        <ul class="mt-3 flex flex-col gap-2.5">
                            @foreach ($service->relatedServices as $related)
                                <li>
                                    <a href="{{ route('services.show', $related) }}" class="text-meta font-medium">{{ $related->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </x-ui.card>
                @endif

                <x-ui.card>
                    <p class="card-kicker">{{ __('Review') }}</p>
                    <dl class="mt-3 flex flex-col gap-2 text-meta text-muted">
                        @if ($service->reviewed_on)
                            <div class="flex justify-between gap-3">
                                <dt>{{ __('Reviewed') }}</dt>
                                <dd class="text-navy">{{ $service->reviewed_on->format('j M Y') }}</dd>
                            </div>
                        @endif
                        @if ($service->reviewer)
                            <div class="flex justify-between gap-3">
                                <dt>{{ __('Reviewer') }}</dt>
                                <dd class="text-navy">{{ $service->reviewer }}</dd>
                            </div>
                        @endif
                        <p class="mt-1 leading-relaxed">
                            {{ __('Official processes change. Requirements are confirmed by a consultant before you approve a quotation.') }}
                        </p>
                    </dl>
                </x-ui.card>
            </aside>
        </div>
    </div>

    <x-public.cta :service="$service" :heading="__('Ready to start :service?', ['service' => $service->name])" />
</x-layouts::public>
