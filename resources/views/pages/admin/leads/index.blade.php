<x-layouts::admin :title="__('Leads')">
    <header class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-eyebrow uppercase text-teal">{{ __('Admin') }}</p>
            <h1 class="mt-2 text-h2">{{ __('Leads') }}</h1>
        </div>

        <form method="GET" action="{{ route('admin.leads.index') }}" class="flex flex-wrap items-end gap-2">
            @if ($activeStatus)
                <input type="hidden" name="status" value="{{ $activeStatus }}">
            @endif
            <x-ui.field :label="__('Search')" for="q" class="w-full sm:w-64">
                <x-ui.input id="q" name="q" :value="request('q')" :placeholder="__('Name, email or reference')" />
            </x-ui.field>
            <x-ui.button variant="secondary" type="submit">{{ __('Search') }}</x-ui.button>
        </form>
    </header>

    <nav class="mt-6 flex flex-wrap gap-2" aria-label="{{ __('Filter by status') }}">
        <a href="{{ route('admin.leads.index', ['q' => request('q')]) }}"
            @class(['badge', $activeStatus === '' ? 'badge-info' : 'badge-neutral', 'no-underline hover:no-underline'])>
            {{ __('All') }}
        </a>
        @foreach ($statuses as $status)
            <a href="{{ route('admin.leads.index', ['status' => $status->value, 'q' => request('q')]) }}"
                @class(['badge', $activeStatus === $status->value ? 'badge-info' : 'badge-neutral', 'no-underline hover:no-underline'])>
                {{ $status->label() }}
            </a>
        @endforeach
        <a href="{{ route('admin.leads.index', ['response' => 'waiting', 'q' => request('q')]) }}"
            @class(['badge', $awaitingResponse ? 'badge-info' : 'badge-neutral', 'no-underline hover:no-underline'])>
            {{ __('No response recorded') }}
        </a>
    </nav>

    @if ($leads->isEmpty())
        <x-ui.empty-state class="mt-6" :title="__('No leads match this view')">
            {{ __('Try a different status filter or clear your search.') }}
        </x-ui.empty-state>
    @else
        <x-ui.table class="mt-6" :caption="__('Leads')">
            <thead>
                <tr>
                    <th scope="col">{{ __('Reference') }}</th>
                    <th scope="col">{{ __('Name') }}</th>
                    <th scope="col">{{ __('Organisation') }}</th>
                    <th scope="col">{{ __('Service') }}</th>
                    <th scope="col">{{ __('Status') }}</th>
                    <th scope="col">{{ __('Assigned') }}</th>
                    <th scope="col">{{ __('First response') }}</th>
                    <th scope="col">{{ __('Received') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($leads as $lead)
                    <tr>
                        <th scope="row" class="font-mono font-normal">
                            <a href="{{ route('admin.leads.show', $lead) }}">{{ $lead->reference }}</a>
                        </th>
                        <td>
                            {{ $lead->name }}
                            <span class="block card-meta">{{ $lead->email }}</span>
                        </td>
                        <td class="text-muted">{{ $lead->organisation_name ?? '—' }}</td>
                        <td class="text-muted">{{ $lead->service?->name ?? __('Not specified') }}</td>
                        <td><x-ui.badge :variant="$lead->status->badge()">{{ $lead->status->label() }}</x-ui.badge></td>
                        <td class="text-muted">{{ $lead->assignee?->name ?? '—' }}</td>
                        <td class="text-muted">{{ $lead->first_responded_at?->format('j M Y, H:i') ?? __('Not recorded') }}</td>
                        <td class="text-muted">{{ $lead->created_at->format('j M Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </x-ui.table>

        <div class="mt-6">{{ $leads->links() }}</div>
    @endif
</x-layouts::admin>
