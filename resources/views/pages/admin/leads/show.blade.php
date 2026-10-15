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
                        __('Assigned at') => $lead->assigned_at?->format('j M Y, H:i'),
                        __('First human response') => $lead->first_responded_at?->format('j M Y, H:i'),
                        __('Response recorded by') => $lead->responseRecorder?->name,
                        __('Campaign source') => $lead->utm_source,
                        __('Campaign medium') => $lead->utm_medium,
                        __('Campaign name') => $lead->utm_campaign,
                    ] as $label => $value)
                        <div>
                            <dt class="card-meta">{{ $label }}</dt>
                            <dd class="text-ink">{{ $value ?: '—' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </x-ui.card>

            @if ($lead->assignee && ! $lead->assignee->isAdmin())
                <x-ui.alert variant="attention" :title="__('Review this assignment')">
                    {{ __('This lead is assigned to an account that does not manage ITM Business Solutions leads. Reassign it to an administrator.') }}
                </x-ui.alert>
            @endif

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

            <x-ui.card>
                <h2 class="card-title">{{ __('Optional email updates') }}</h2>
                @if ($requestEvent = $lead->marketingConsentEvents->firstWhere('action', 'requested'))
                    <p class="card-body mt-3">
                        {{ __('Requested through :source on :date (wording :version).', [
                            'source' => str_replace('_', ' ', $requestEvent->source),
                            'date' => $requestEvent->occurred_at->format('j M Y, H:i'),
                            'version' => $requestEvent->policy_version,
                        ]) }}
                    </p>
                    <p class="card-meta mt-2">
                        @if ($marketingSubscription?->isActive())
                            {{ __('Email permission confirmed') }}
                        @elseif ($marketingSubscription?->revoked_at)
                            {{ __('Unsubscribed — do not send marketing email') }}
                        @else
                            {{ __('Awaiting email confirmation — do not send marketing email') }}
                        @endif
                    </p>
                @else
                    <p class="card-body mt-3">{{ __('No marketing opt-in was requested on this enquiry.') }}</p>
                @endif
            </x-ui.card>
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
                    <x-ui.button variant="secondary" href="mailto:{{ $lead->email }}?subject={{ rawurlencode('Your ITM Business Solutions request '.$lead->reference) }}">
                        {{ __('Email this person') }}
                    </x-ui.button>
                    @if ($lead->phone)
                        <x-ui.button variant="whatsapp" icon="whatsapp-logo"
                            href="https://wa.me/{{ preg_replace('/\D/', '', $lead->phone) }}?text={{ rawurlencode('Hello '.$lead->name.', regarding your ITM Business Solutions request '.$lead->reference.':') }}">
                            {{ __('Open WhatsApp') }}
                        </x-ui.button>
                    @endif
                </div>
                @if (! $lead->first_responded_at)
                    <form method="POST" action="{{ route('admin.leads.response', $lead) }}" class="mt-5">
                        @csrf
                        <p class="card-meta mb-3">{{ __('After you have personally replied, record the first human response here. Opening email or WhatsApp alone does not count.') }}</p>
                        <x-ui.button type="submit" variant="secondary" class="btn-block">{{ __('Record first response') }}</x-ui.button>
                    </form>
                @endif
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
