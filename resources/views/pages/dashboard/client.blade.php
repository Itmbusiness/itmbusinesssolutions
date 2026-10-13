<div class="page-shell py-8">
    <header>
        <p class="text-eyebrow uppercase text-teal">Workspace</p>
        <h1 class="mt-2 text-h2">{{ __('Overview') }}</h1>
    </header>

    @if (session('status'))
        <x-ui.alert class="mt-6" variant="success" title="{{ session('status') }}" />
    @endif

    {{--
        Design system §08. Quotations and documents aren't modelled yet (PRD
        Release 2/3), so those cards stay literal placeholders — never invented
        figures. Requests are real: they come straight from this client's leads.
    --}}
    <div class="mt-6 grid gap-3.5 md:grid-cols-3">
        <x-ui.card kicker="Quotations" title="Awaiting review">
            <p class="mt-1 text-h1 tabular-nums">—</p>
            <p class="card-meta mt-1">No quotations yet</p>
        </x-ui.card>

        <x-ui.card kicker="Requests" title="In progress">
            <p class="mt-1 text-h1 tabular-nums">{{ $openLeadCount }}</p>
            <p class="card-meta mt-1">{{ $openLeadCount === 0 ? 'No open requests' : 'Being worked on' }}</p>
        </x-ui.card>

        <x-ui.card navy kicker="Action required" title="Waiting on documents">
            <p class="mt-1 text-h1 tabular-nums">—</p>
            <p class="mt-1 text-meta text-navy-300">Nothing needs your attention</p>
        </x-ui.card>
    </div>

    <x-ui.alert class="mt-3.5" variant="info" title="Requirements may change after assessment">
        This workspace helps you prepare. A consultant confirms the final list of
        documents in your written quotation.
    </x-ui.alert>

    <section class="mt-8" aria-labelledby="dash-applications">
        <h2 id="dash-applications" class="text-h3">{{ __('Requests') }}</h2>

        @if ($leads->isEmpty())
            <x-ui.empty-state class="mt-3.5" title="No requests yet">
                When you request a service, it will appear here with its progress.

                <x-slot:action>
                    <x-ui.button icon="paper-plane-tilt" href="{{ route('service-requests.create') }}">
                        {{ __('Request a Service') }}
                    </x-ui.button>
                </x-slot:action>
            </x-ui.empty-state>
        @else
            <x-ui.table class="mt-3.5" :caption="__('Your requests')">
                <thead>
                    <tr>
                        <th scope="col">{{ __('Reference') }}</th>
                        <th scope="col">{{ __('Service') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Sent') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leads as $lead)
                        <tr>
                            <th scope="row" class="font-mono font-normal">{{ $lead->reference }}</th>
                            <td class="text-muted">{{ $lead->service?->name ?? __('Not specified') }}</td>
                            <td><x-ui.badge :variant="$lead->status->badge()">{{ $lead->status->label() }}</x-ui.badge></td>
                            <td class="text-muted">{{ $lead->created_at->format('j M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </x-ui.table>

            <x-ui.button class="mt-3.5" variant="secondary" icon="paper-plane-tilt" href="{{ route('service-requests.create') }}">
                {{ __('Request another service') }}
            </x-ui.button>
        @endif
    </section>
</div>
