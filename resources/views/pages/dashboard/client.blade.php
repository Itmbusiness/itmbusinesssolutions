<div class="page-shell py-8">
    <header>
        <p class="text-eyebrow uppercase text-teal">Workspace</p>
        <h1 class="mt-2 text-h2">{{ __('Overview') }}</h1>
    </header>

    @if (session('status'))
        <x-ui.alert class="mt-6" variant="success" title="{{ session('status') }}" />
    @endif

    {{--
        Design system §08. Counts are placeholders until the applications and
        quotations models exist — the board is explicit that no figures,
        fees or statuses are invented, so these read as empty, not as data.
    --}}
    <div class="mt-6 grid gap-3.5 md:grid-cols-3">
        <x-ui.card kicker="Quotations" title="Awaiting review">
            <p class="mt-1 text-h1 tabular-nums">—</p>
            <p class="card-meta mt-1">No quotations yet</p>
        </x-ui.card>

        <x-ui.card kicker="Applications" title="In progress">
            <p class="mt-1 text-h1 tabular-nums">—</p>
            <p class="card-meta mt-1">No applications yet</p>
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
        <h2 id="dash-applications" class="text-h3">{{ __('Applications') }}</h2>

        <x-ui.empty-state class="mt-3.5" title="No applications yet">
            When you request a service for your organisation, it will appear here
            with its progress.

            <x-slot:action>
                <x-ui.button icon="paper-plane-tilt" href="{{ route('service-requests.create') }}">
                    {{ __('Request a Service') }}
                </x-ui.button>
            </x-slot:action>
        </x-ui.empty-state>
    </section>
</div>
