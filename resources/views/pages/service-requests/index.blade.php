<x-layouts::app :title="__('Your requests')">
    <div class="page-shell py-8">
        <header class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-eyebrow uppercase text-teal">{{ __('Workspace') }}</p>
                <h1 class="mt-2 text-h2">{{ __('Your requests') }}</h1>
                <p class="mt-2 text-muted">{{ __('All service requests sent from your account.') }}</p>
            </div>
            <x-ui.button icon="paper-plane-tilt" :href="route('service-requests.create')">
                {{ __('Request a Service') }}
            </x-ui.button>
        </header>

        @if ($leads->isEmpty())
            <x-ui.empty-state class="mt-6" :title="__('No requests yet')">
                {{ __('When you request a service, it will appear here.') }}
            </x-ui.empty-state>
        @else
            <x-ui.table class="mt-6" :caption="__('Your service requests')">
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

            <div class="mt-6">{{ $leads->links() }}</div>
        @endif
    </div>
</x-layouts::app>
