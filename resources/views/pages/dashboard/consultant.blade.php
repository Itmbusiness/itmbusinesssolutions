<div class="page-shell py-8">
    <header class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-eyebrow uppercase text-teal">My business</p>
            <h1 class="mt-2 text-h2">{{ __('Overview') }}</h1>
        </div>

        <x-ui.button icon="user-plus" href="{{ route('consultant.customers.create') }}">
            {{ __('Add customer') }}
        </x-ui.button>
    </header>

    @if (session('status'))
        <x-ui.alert class="mt-6" variant="success" title="{{ session('status') }}" />
    @endif

    {{--
        This is entirely the consultant's own book — never ITM's leads,
        quotations or clients. See docs/dashboards-spec.md.
    --}}
    <div class="mt-6 grid gap-3.5 md:grid-cols-2">
        <x-ui.card kicker="Customers" title="Your book">
            <p class="mt-1 text-h1 tabular-nums">{{ $customerCount }}</p>
            <p class="card-meta mt-1">{{ $customerCount === 0 ? 'No customers yet' : 'Total customers' }}</p>
        </x-ui.card>

        <x-ui.card navy kicker="Tasks" title="Open follow-ups">
            <p class="mt-1 text-h1 tabular-nums">{{ $openTaskCount }}</p>
            <p class="mt-1 text-meta text-navy-300">{{ $openTaskCount === 0 ? 'Nothing due' : 'Waiting on you' }}</p>
        </x-ui.card>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <section aria-labelledby="dash-customers">
            <h2 id="dash-customers" class="text-h3">{{ __('Recent customers') }}</h2>

            @if ($customers->isEmpty())
                <x-ui.empty-state class="mt-3.5" icon="users" title="No customers yet">
                    Add your first customer to start tracking your own work here.

                    <x-slot:action>
                        <x-ui.button icon="user-plus" href="{{ route('consultant.customers.create') }}">
                            {{ __('Add customer') }}
                        </x-ui.button>
                    </x-slot:action>
                </x-ui.empty-state>
            @else
                <ul class="mt-3.5 flex flex-col gap-2">
                    @foreach ($customers as $customer)
                        <li class="card flex items-center justify-between gap-3 p-3">
                            <a href="{{ route('consultant.customers.show', $customer) }}" class="text-sm font-medium">
                                {{ $customer->name }}
                            </a>
                            <x-ui.badge :variant="$customer->stage->badge()">{{ $customer->stage->label() }}</x-ui.badge>
                        </li>
                    @endforeach
                </ul>

                <x-ui.button class="mt-3.5" variant="secondary" href="{{ route('consultant.customers.index') }}">
                    {{ __('View all customers') }}
                </x-ui.button>
            @endif
        </section>

        <section aria-labelledby="dash-tasks">
            <h2 id="dash-tasks" class="text-h3">{{ __('My tasks') }}</h2>

            @if ($tasks->isEmpty())
                <x-ui.empty-state class="mt-3.5" icon="check-circle" title="No tasks due">
                    Follow-up reminders you add for a customer will appear here.
                </x-ui.empty-state>
            @else
                <ul class="mt-3.5 flex flex-col gap-2">
                    @foreach ($tasks as $task)
                        <li class="card flex items-center justify-between gap-3 p-3">
                            <div class="flex items-center gap-3">
                                <form method="POST" action="{{ route('consultant.tasks.toggle', $task) }}">
                                    @csrf
                                    @method('PATCH')
                                    <x-ui.button type="submit" variant="icon" icon="circle" aria-label="{{ __('Mark task done') }}" />
                                </form>
                                <div>
                                    <p class="text-sm">{{ $task->title }}</p>
                                    <p class="card-meta">
                                        {{ $task->customer->name }}
                                        @if ($task->due_on)
                                            · {{ __('Due') }} {{ $task->due_on->format('j M Y') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
</div>
