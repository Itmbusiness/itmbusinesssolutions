<x-layouts::admin :title="$lead->reference">
    <header class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <a href="{{ route('admin.leads.index') }}" class="text-meta text-teal">&larr; {{ __('All leads') }}</a>
            <h1 class="mt-2 font-mono text-h2">{{ $lead->reference }}</h1>
            <p class="card-meta mt-1">{{ __('Received :date', ['date' => $lead->created_at->format('j M Y, H:i')]) }}</p>
        </div>
        <x-ui.badge :variant="$lead->status->badge()">{{ $lead->status->label() }}</x-ui.badge>
    </header>

    <div class="mt-6 grid gap-4 lg:grid-cols-[minmax(0,1fr)_340px]">
        <div class="flex flex-col gap-4">
            <x-ui.card>
                <h2 class="card-title">{{ __('Contact') }}</h2>
                <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                    @foreach ([
                        __('Name') => $lead->name,
                        __('Email') => $lead->email,
                        __('Phone') => $lead->phone,
                        __('Preferred channel') => Str::headline($lead->preferred_channel),
                        __('Organisation') => $lead->organisation_name,
                        __('Organisation status') => $lead->organisation_status ? Str::headline($lead->organisation_status) : null,
                        __('Urgency') => $lead->urgency ? Str::headline($lead->urgency) : null,
                        __('Source') => Str::headline($lead->source),
                    ] as $label => $value)
                        <div>
                            <dt class="card-meta">{{ $label }}</dt>
                            <dd class="text-ink">{{ $value ?: '—' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </x-ui.card>

            <x-ui.card>
                <h2 class="card-title">{{ __('What they asked for') }}</h2>
                <dl class="mt-4 flex flex-col gap-4">
                    <div>
                        <dt class="card-meta">{{ __('Service') }}</dt>
                        <dd class="text-ink">
                            @if ($lead->service)
                                <a href="{{ route('services.show', $lead->service) }}">{{ $lead->service->name }}</a>
                            @else
                                {{ __('Not specified') }}
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="card-meta">{{ __('Goal') }}</dt>
                        <dd class="text-ink">{{ $lead->goal ? Str::headline($lead->goal) : '—' }}</dd>
                    </div>
                    <div>
                        <dt class="card-meta">{{ __('Message') }}</dt>
                        <dd class="mt-1 whitespace-pre-line text-ink">{{ $lead->message ?: '—' }}</dd>
                    </div>
                </dl>
            </x-ui.card>

            @if ($lead->consent_at)
                <x-ui.alert variant="success" :title="__('Consent recorded')">
                    {{ __('Given :date.', ['date' => $lead->consent_at->format('j M Y, H:i')]) }}
                </x-ui.alert>
            @else
                <x-ui.alert variant="attention" :title="__('No consent recorded')">
                    {{ __('This request predates consent capture. Confirm before contacting.') }}
                </x-ui.alert>
            @endif
        </div>

        <aside class="flex flex-col gap-4">
            <x-ui.card class="elev-md">
                <h2 class="card-title">{{ __('Manage') }}</h2>

                <form method="POST" action="{{ route('admin.leads.update', $lead) }}" class="mt-4 flex flex-col gap-5">
                    @csrf
                    @method('PATCH')

                    <x-ui.field :label="__('Status')" for="status">
                        <x-ui.select id="status" name="status">
                            @foreach ($statuses as $status)
                                <option value="{{ $status->value }}" @selected(old('status', $lead->status->value) === $status->value)>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </x-ui.select>
                    </x-ui.field>

                    <x-ui.field :label="__('Assigned to')" for="assigned_to">
                        <x-ui.select id="assigned_to" name="assigned_to">
                            <option value="">{{ __('Unassigned') }}</option>
                            @foreach ($assignees as $assignee)
                                <option value="{{ $assignee->id }}" @selected(old('assigned_to', $lead->assigned_to) == $assignee->id)>
                                    {{ $assignee->name }}
                                </option>
                            @endforeach
                        </x-ui.select>
                    </x-ui.field>

                    <x-ui.field :label="__('Internal notes')" for="internal_notes"
                        hint="{{ __('Never shown to the client.') }}">
                        <x-ui.textarea id="internal_notes" name="internal_notes">{{ old('internal_notes', $lead->internal_notes) }}</x-ui.textarea>
                    </x-ui.field>

                    <x-ui.button type="submit" class="btn-block">{{ __('Save') }}</x-ui.button>
                </form>
            </x-ui.card>

            <x-ui.card>
                <h2 class="card-title">{{ __('Reply') }}</h2>
                <div class="mt-3 flex flex-col gap-2">
                    <x-ui.button variant="secondary" href="mailto:{{ $lead->email }}?subject={{ rawurlencode('Your ITM request '.$lead->reference) }}">
                        {{ __('Email this person') }}
                    </x-ui.button>
                    @if ($lead->phone)
                        <x-ui.button variant="whatsapp" icon="whatsapp-logo"
                            href="https://wa.me/{{ preg_replace('/\D/', '', $lead->phone) }}?text={{ rawurlencode('Hello '.$lead->name.', regarding your ITM request '.$lead->reference.':') }}">
                            {{ __('Open WhatsApp') }}
                        </x-ui.button>
                    @endif
                </div>
            </x-ui.card>

            <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}"
                onsubmit="return confirm('{{ __('Delete this lead permanently?') }}')">
                @csrf
                @method('DELETE')
                <x-ui.button type="submit" variant="quiet" class="btn-block">{{ __('Delete lead') }}</x-ui.button>
            </form>
        </aside>
    </div>
</x-layouts::admin>
