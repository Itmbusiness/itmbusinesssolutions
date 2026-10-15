<x-layouts::app :title="__('Request a Service')">
    <div class="page-shell max-w-160 py-8">
        <header>
            <p class="text-eyebrow uppercase text-teal">{{ __('Step 1 of the process') }}</p>
            <h1 class="mt-2 text-h2">{{ __('Request a Service') }}</h1>
            <p class="mt-2 text-lead text-ink">
                {{ __('A short request — no documents, no payment. A consultant reviews it and follows up with a written quotation.') }}
            </p>
        </header>

        <form method="POST" action="{{ route('service-requests.store') }}" class="mt-8 flex flex-col gap-6">
            @csrf

            <x-ui.field legend="{{ __('What do you need?') }}" :error="$errors->first('goal')">
                <div class="flex flex-wrap gap-2">
                    @foreach ($goals as $goal)
                        <x-ui.choice name="goal" :value="$goal->value" :checked="old('goal') === $goal->value" :required="$loop->first">
                            {{ $goal->label() }}
                        </x-ui.choice>
                    @endforeach
                </div>
            </x-ui.field>

            <x-ui.field :label="__('A specific service?')" for="service_id" :error="$errors->first('service_id')"
                hint="{{ __('Optional — leave blank if you are not sure which one applies.') }}">
                <x-ui.select id="service_id" name="service_id">
                    <option value="">{{ __('Not sure yet') }}</option>
                    @foreach ($services as $categoryName => $group)
                        <optgroup label="{{ $categoryName }}">
                            @foreach ($group as $option)
                                <option value="{{ $option->id }}" @selected(old('service_id') == $option->id)>{{ $option->name }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </x-ui.select>
            </x-ui.field>

            <x-ui.field
                :label="__('Anything specific we should know?')"
                for="message"
                hint="{{ __('Optional — a consultant will confirm the details with you.') }}"
                :error="$errors->first('message')"
            >
                <x-ui.textarea id="message" name="message" :invalid="$errors->has('message')">{{ old('message') }}</x-ui.textarea>
            </x-ui.field>

            <x-ui.field legend="{{ __('Preferred channel') }}" :error="$errors->first('preferred_channel')">
                <div class="flex flex-wrap gap-2">
                    @foreach (['whatsapp' => __('WhatsApp'), 'email' => __('Email'), 'phone' => __('Phone call')] as $value => $label)
                        <x-ui.choice name="preferred_channel" :value="$value"
                            :checked="old('preferred_channel', 'whatsapp') === $value" :required="$loop->first">
                            {{ $label }}
                        </x-ui.choice>
                    @endforeach
                </div>
            </x-ui.field>

            <x-ui.field :error="$errors->first('consent')">
                <x-ui.choice type="checkbox" name="consent" value="1" required>
                    {{ __('I agree that ITM Business Solutions may contact me about this request and store the information I have provided.') }}
                </x-ui.choice>
            </x-ui.field>

            <x-public.marketing-opt-in />

            <div class="flex items-center gap-3">
                <x-ui.button type="submit" icon="paper-plane-tilt">{{ __('Send Request') }}</x-ui.button>
                <x-ui.button variant="quiet" href="{{ route('dashboard') }}">{{ __('Cancel') }}</x-ui.button>
            </div>
        </form>
    </div>
</x-layouts::app>
