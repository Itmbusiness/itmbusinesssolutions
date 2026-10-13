<x-layouts::app :title="$customer->name">
    <div class="page-shell py-8">
        <header class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-eyebrow uppercase text-teal">{{ __('Consultant') }}</p>
                <h1 class="mt-2 text-h2">{{ $customer->name }}</h1>
                <p class="mt-1 text-meta text-muted">
                    {{ $customer->email ?? __('No email') }} · {{ $customer->phone ?? __('No phone') }}
                </p>
            </div>

            <div class="flex items-center gap-2">
                <x-ui.badge :variant="$customer->stage->badge()">{{ $customer->stage->label() }}</x-ui.badge>
                <x-ui.button variant="secondary" icon="pencil-simple" href="{{ route('consultant.customers.edit', $customer) }}">
                    {{ __('Edit') }}
                </x-ui.button>
            </div>
        </header>

        @if (session('status'))
            <x-ui.alert class="mt-6" variant="success" title="{{ session('status') }}" />
        @endif

        <div class="mt-8 grid gap-6 lg:grid-cols-2">
            <section aria-labelledby="tasks-heading">
                <h2 id="tasks-heading" class="text-h3">{{ __('Tasks') }}</h2>

                @if ($customer->tasks->isEmpty())
                    <x-ui.empty-state class="mt-3.5" icon="check-circle" :title="__('No tasks yet')">
                        {{ __('Add a reminder to follow up with this customer.') }}
                    </x-ui.empty-state>
                @else
                    <ul class="mt-3.5 flex flex-col gap-2">
                        @foreach ($customer->tasks as $task)
                            <li class="card flex items-center justify-between gap-3 p-3">
                                <div class="flex items-center gap-3">
                                    <form method="POST" action="{{ route('consultant.tasks.toggle', $task) }}">
                                        @csrf
                                        @method('PATCH')
                                        <x-ui.button
                                            type="submit"
                                            variant="icon"
                                            :icon="$task->completed_at ? 'check-circle' : 'circle'"
                                            aria-label="{{ $task->completed_at ? __('Reopen task') : __('Mark task done') }}"
                                        />
                                    </form>
                                    <div>
                                        <p @class(['text-sm', 'line-through text-muted' => $task->completed_at])>{{ $task->title }}</p>
                                        @if ($task->due_on)
                                            <p class="card-meta">{{ __('Due') }} {{ $task->due_on->format('j M Y') }}</p>
                                        @endif
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('consultant.tasks.destroy', $task) }}"
                                    onsubmit="return confirm('{{ __('Remove this task?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <x-ui.button type="submit" variant="ghost" icon="trash" />
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <form method="POST" action="{{ route('consultant.customers.tasks.store', $customer) }}" class="mt-4 flex flex-wrap items-end gap-2">
                    @csrf
                    <x-ui.field :label="__('New task')" for="title" class="flex-1">
                        <x-ui.input id="title" name="title" :placeholder="__('Follow up about...')" required />
                    </x-ui.field>
                    <x-ui.field :label="__('Due')" for="due_on">
                        <x-ui.input id="due_on" name="due_on" type="date" />
                    </x-ui.field>
                    <x-ui.button type="submit" variant="secondary">{{ __('Add task') }}</x-ui.button>
                </form>
            </section>

            <section aria-labelledby="notes-heading">
                <h2 id="notes-heading" class="text-h3">{{ __('Notes') }}</h2>

                @if ($customer->notes->isEmpty())
                    <x-ui.empty-state class="mt-3.5" icon="note" :title="__('No notes yet')">
                        {{ __('Keep a record of what you\'ve done for this customer.') }}
                    </x-ui.empty-state>
                @else
                    <ul class="mt-3.5 flex flex-col gap-2">
                        @foreach ($customer->notes as $note)
                            <li class="card p-3">
                                <p class="text-sm">{{ $note->body }}</p>
                                <p class="mt-1 card-meta">{{ $note->created_at->format('j M Y, g:ia') }}</p>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <form method="POST" action="{{ route('consultant.customers.notes.store', $customer) }}" class="mt-4">
                    @csrf
                    <x-ui.field :label="__('New note')" for="body" :error="$errors->first('body')">
                        <x-ui.textarea id="body" name="body" :placeholder="__('What happened?')" required></x-ui.textarea>
                    </x-ui.field>
                    <x-ui.button type="submit" variant="secondary" class="mt-2">{{ __('Add note') }}</x-ui.button>
                </form>
            </section>
        </div>
    </div>
</x-layouts::app>
