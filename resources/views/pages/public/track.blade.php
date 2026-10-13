<x-layouts::public
    :title="__('Track Application')"
    :meta-description="__('Check the progress of an ITM Business Solutions request using your reference and email address.')"
    :indexable="false"
>
    <x-public.page-header
        :eyebrow="__('Track')"
        :heading="__('Check your request')"
        :lead="__('Enter the reference from your confirmation together with the email address you used.')"
    />

    <div class="page-shell section-tight">
        <x-ui.card>
            <form method="POST" action="{{ route('track.lookup') }}" class="flex flex-col gap-5">
                @csrf

                <x-ui.field :label="__('Reference')" for="reference" :error="$errors->first('reference')"
                    hint="{{ __('For example, LEAD-4B7K2M.') }}">
                    <x-ui.input id="reference" name="reference" :value="old('reference')" :invalid="$errors->has('reference')" required />
                </x-ui.field>

                <x-ui.field :label="__('Email address')" for="email" :error="$errors->first('email')">
                    <x-ui.input id="email" name="email" type="email" :value="old('email')" :invalid="$errors->has('email')" required autocomplete="email" />
                </x-ui.field>

                <div>
                    <x-ui.button type="submit" icon="magnifying-glass">{{ __('Check progress') }}</x-ui.button>
                </div>
            </form>
        </x-ui.card>

        @if ($searched)
            <div class="mt-8">
                @if ($lead)
                    <x-ui.card class="elev-md">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <p class="card-kicker text-teal">{{ $lead->reference }}</p>
                                <h2 class="card-title mt-2">{{ $lead->service?->name ?? __('Service being confirmed') }}</h2>
                            </div>
                            <x-ui.badge :variant="$lead->status->badge()">{{ $lead->status->label() }}</x-ui.badge>
                        </div>

                        <dl class="mt-5 grid gap-3 sm:grid-cols-2">
                            <div>
                                <dt class="card-meta">{{ __('Requested') }}</dt>
                                <dd class="text-ink">{{ $lead->created_at->format('j M Y') }}</dd>
                            </div>
                            <div>
                                <dt class="card-meta">{{ __('Preferred channel') }}</dt>
                                <dd class="text-ink">{{ Str::headline($lead->preferred_channel) }}</dd>
                            </div>
                        </dl>

                        {{-- Guest tracking deliberately shows status only. Internal
                             notes and assignee never appear here (FR-09). --}}
                        <p class="field-hint mt-5">
                            {{ __('This view shows progress only. For full detail, sign in to your workspace or reply on your preferred channel.') }}
                        </p>
                    </x-ui.card>
                @else
                    <x-ui.alert variant="attention" :title="__('No matching request found')">
                        {{ __('Check that the reference and email address match the ones on your confirmation. If you are still stuck, contact us and we will find it for you.') }}
                    </x-ui.alert>
                @endif
            </div>
        @endif
    </div>
</x-layouts::public>
