<x-layouts::app :title="__('My customers')">
    <div class="page-shell py-8">
        <header class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-eyebrow uppercase text-teal">{{ __('Consultant') }}</p>
                <h1 class="mt-2 text-h2">{{ __('My customers') }}</h1>
            </div>

            <x-ui.button icon="user-plus" href="{{ route('consultant.customers.create') }}">
                {{ __('Add customer') }}
            </x-ui.button>
        </header>

        @if (session('status'))
            <x-ui.alert class="mt-6" variant="success" title="{{ session('status') }}" />
        @endif

        <form method="GET" action="{{ route('consultant.customers.index') }}" class="mt-6 flex flex-wrap items-end gap-2">
            @if ($activeStage)
                <input type="hidden" name="stage" value="{{ $activeStage }}">
            @endif
            <x-ui.field :label="__('Search')" for="q" class="w-full sm:w-64">
                <x-ui.input id="q" name="q" :value="request('q')" :placeholder="__('Name or email')" />
            </x-ui.field>
            <x-ui.button variant="secondary" type="submit">{{ __('Search') }}</x-ui.button>
        </form>

        <nav class="mt-6 flex flex-wrap gap-2" aria-label="{{ __('Filter by stage') }}">
            <a href="{{ route('consultant.customers.index', ['q' => request('q')]) }}"
                @class(['badge', $activeStage === '' ? 'badge-info' : 'badge-neutral', 'no-underline hover:no-underline'])>
                {{ __('All') }}
            </a>
            @foreach ($stages as $stage)
                <a href="{{ route('consultant.customers.index', ['stage' => $stage->value, 'q' => request('q')]) }}"
                    @class(['badge', $activeStage === $stage->value ? 'badge-info' : 'badge-neutral', 'no-underline hover:no-underline'])>
                    {{ $stage->label() }}
                </a>
            @endforeach
        </nav>

        @if ($customers->isEmpty())
            <x-ui.empty-state class="mt-6" icon="users" :title="__('No customers yet')">
                {{ __('Add your first customer to start tracking your own work here.') }}

                <x-slot:action>
                    <x-ui.button icon="user-plus" href="{{ route('consultant.customers.create') }}">
                        {{ __('Add customer') }}
                    </x-ui.button>
                </x-slot:action>
            </x-ui.empty-state>
        @else
            <x-ui.table class="mt-6" :caption="__('My customers')">
                <thead>
                    <tr>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Phone') }}</th>
                        <th scope="col">{{ __('Stage') }}</th>
                        <th scope="col">{{ __('Added') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <th scope="row" class="font-normal">
                                <a href="{{ route('consultant.customers.show', $customer) }}">{{ $customer->name }}</a>
                                <span class="block card-meta">{{ $customer->email ?? '—' }}</span>
                            </th>
                            <td class="text-muted">{{ $customer->phone ?? '—' }}</td>
                            <td><x-ui.badge :variant="$customer->stage->badge()">{{ $customer->stage->label() }}</x-ui.badge></td>
                            <td class="text-muted">{{ $customer->created_at->format('j M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </x-ui.table>

            <div class="mt-6">{{ $customers->links() }}</div>
        @endif
    </div>
</x-layouts::app>
