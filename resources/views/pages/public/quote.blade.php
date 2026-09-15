<x-layouts::public
    :title="__('Request a Quotation')"
    :meta-description="__('Tell ITM what you need. No account, no documents and no payment to start — a consultant reviews every request and replies with a written quotation.')"
    :indexable="false"
>
    <x-public.page-header
        :eyebrow="__('Step 1 of the process')"
        :heading="$service ? __('Request a quotation for :service', ['service' => $service->name]) : __('Request a written quotation')"
        :lead="__('No account, no documents and no payment to start. A consultant reviews your request and confirms what is actually needed.')"
    />

    <div class="page-shell section-tight">
        @if ($errors->any())
            <x-ui.alert variant="problem" title="{{ __('Please check the form') }}" class="mb-8">
                {{ __('Some details need attention before this request can be sent.') }}
            </x-ui.alert>
        @endif

        <form method="POST" action="{{ route('quote.store') }}" class="flex flex-col gap-6">
            @csrf

            {{-- Honeypot: hidden from people, irresistible to bots. --}}
            <div class="hidden" aria-hidden="true">
                <label for="website">{{ __('Leave this field empty') }}</label>
                <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
            </div>

            <x-ui.card>
                <h2 class="text-h3 font-semibold tracking-tight">{{ __('What do you need?') }}</h2>

                <div class="mt-5 flex flex-col gap-6">
                    <x-ui.field :label="__('Service')" for="service_id" :error="$errors->first('service_id')"
                        hint="{{ __('Not sure? Leave this blank and describe your goal below.') }}">
                        <x-ui.select id="service_id" name="service_id">
                            <option value="">{{ __('I am not sure yet') }}</option>
                            @foreach ($services as $categoryName => $group)
                                <optgroup label="{{ $categoryName }}">
                                    @foreach ($group as $option)
                                        <option value="{{ $option->id }}" @selected(old('service_id', $service?->id) == $option->id)>
                                            {{ $option->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </x-ui.select>
                    </x-ui.field>

                    <x-ui.field legend="{{ __('What is your goal?') }}" :error="$errors->first('goal')">
                        <div class="flex flex-wrap gap-2">
                            @foreach ($goals as $goal)
                                <x-ui.choice name="goal" :value="$goal->value" :checked="old('goal') === $goal->value">
                                    {{ $goal->label() }}
                                </x-ui.choice>
                            @endforeach
                        </div>
                    </x-ui.field>

                    <x-ui.field :label="__('Anything specific we should know?')" for="message" :error="$errors->first('message')"
                        hint="{{ __('Optional. Do not include passwords, banking PINs or one-time codes.') }}">
                        <x-ui.textarea id="message" name="message" :invalid="$errors->has('message')">{{ old('message') }}</x-ui.textarea>
                    </x-ui.field>
                </div>
            </x-ui.card>

            <x-ui.card>
                <h2 class="text-h3 font-semibold tracking-tight">{{ __('About your organisation') }}</h2>

                <div class="mt-5 flex flex-col gap-6">
                    <x-ui.field :label="__('Organisation name')" for="organisation_name" :error="$errors->first('organisation_name')"
                        hint="{{ __('If you have not registered yet, use your preferred name.') }}">
                        <x-ui.input id="organisation_name" name="organisation_name" :value="old('organisation_name')" :invalid="$errors->has('organisation_name')" />
                    </x-ui.field>

                    <x-ui.field legend="{{ __('Is it already registered?') }}" :error="$errors->first('organisation_status')">
                        <div class="flex flex-wrap gap-2">
                            @foreach ([
                                'not-registered' => __('Not registered yet'),
                                'registered' => __('Already registered'),
                                'unsure' => __('I am not sure'),
                            ] as $value => $label)
                                <x-ui.choice name="organisation_status" :value="$value" :checked="old('organisation_status') === $value">
                                    {{ $label }}
                                </x-ui.choice>
                            @endforeach
                        </div>
                    </x-ui.field>

                    <x-ui.field legend="{{ __('How soon do you need this?') }}" :error="$errors->first('urgency')">
                        <div class="flex flex-wrap gap-2">
                            @foreach ([
                                'exploring' => __('Just exploring'),
                                'this-month' => __('This month'),
                                'urgent' => __('Urgent'),
                            ] as $value => $label)
                                <x-ui.choice name="urgency" :value="$value" :checked="old('urgency') === $value">
                                    {{ $label }}
                                </x-ui.choice>
                            @endforeach
                        </div>
                    </x-ui.field>
                </div>
            </x-ui.card>

            <x-ui.card>
                <h2 class="text-h3 font-semibold tracking-tight">{{ __('How can we reach you?') }}</h2>

                <div class="mt-5 flex flex-col gap-6">
                    <x-ui.field :label="__('Your name')" for="name" :error="$errors->first('name')">
                        <x-ui.input id="name" name="name" :value="old('name', auth()->user()?->name)" :invalid="$errors->has('name')" required autocomplete="name" />
                    </x-ui.field>

                    <x-ui.field :label="__('Email address')" for="email" :error="$errors->first('email')">
                        <x-ui.input id="email" name="email" type="email" :value="old('email', auth()->user()?->email)" :invalid="$errors->has('email')" required autocomplete="email" />
                    </x-ui.field>

                    <x-ui.field :label="__('Phone or WhatsApp number')" for="phone" :error="$errors->first('phone')">
                        <x-ui.input id="phone" name="phone" type="tel" :value="old('phone')" :invalid="$errors->has('phone')" autocomplete="tel" />
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
                        <x-ui.choice type="checkbox" name="consent" value="1" :checked="(bool) old('consent')" required>
                            {{ __('I agree that ITM may contact me about this request and store the information I have provided.') }}
                        </x-ui.choice>
                    </x-ui.field>
                </div>
            </x-ui.card>

            <div class="flex flex-wrap items-center gap-3">
                <x-ui.button type="submit" icon="paper-plane-tilt">{{ __('Send Request') }}</x-ui.button>
                <p class="text-meta text-muted">{{ __('No payment is requested until you approve a written quotation.') }}</p>
            </div>
        </form>
    </div>
</x-layouts::public>
