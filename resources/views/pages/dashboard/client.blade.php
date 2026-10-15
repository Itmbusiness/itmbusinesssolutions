<div class="page-shell py-8">
    <header class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-eyebrow uppercase text-teal">Workspace</p>
            <h1 class="mt-2 text-h2">{{ __('Your requests') }}</h1>
            <p class="mt-2 text-muted">{{ __('Follow the requests you have sent to ITM.') }}</p>
        </div>
        <x-ui.button icon="paper-plane-tilt" href="{{ route('service-requests.create') }}">
            {{ __('Request a Service') }}
        </x-ui.button>
    </header>

    @if (session('status'))
        <x-ui.alert class="mt-6" variant="success" title="{{ session('status') }}" />
    @endif

    <div class="mt-6 grid gap-3.5 sm:grid-cols-3">
        <x-ui.dashboard-stat :label="__('Open requests')" :value="$openLeadCount" :note="__('Current open status')" :href="route('service-requests.index')" />
        <x-ui.dashboard-stat :label="__('Awaiting information')" :value="$awaitingInformationCount" :note="__('ITM may need more details')" :href="route('service-requests.index')" />
        <x-ui.dashboard-stat :label="__('All requests')" :value="$leadCount" :note="__('Since you joined')" :href="route('service-requests.index')" />
    </div>

    <x-ui.alert class="mt-3.5" variant="info" title="Requirements may change after assessment">
        This workspace helps you prepare. A consultant confirms the final list of
        documents in your written quotation.
    </x-ui.alert>

    <section class="mt-8" aria-labelledby="dash-applications">
        <h2 id="dash-applications" class="text-h3">{{ __('Latest requests') }}</h2>

        @if ($leads->isEmpty())
            <x-ui.empty-state class="mt-3.5" title="No requests yet">
                When you request a service, it will appear here with its progress.

                <x-slot:action>
                    <x-ui.button variant="secondary" icon="paper-plane-tilt" href="{{ route('service-requests.create') }}">
                        {{ __('Request a Service') }}
                    </x-ui.button>
                </x-slot:action>
            </x-ui.empty-state>
        @else
            <x-ui.table class="mt-3.5" :caption="__('Your five most recent requests')">
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

            <x-ui.button class="mt-3.5" variant="secondary" :href="route('service-requests.index')">
                {{ __('View all requests') }}
            </x-ui.button>
        @endif
    </section>
</div>
