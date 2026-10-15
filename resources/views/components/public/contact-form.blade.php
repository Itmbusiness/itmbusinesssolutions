@props(['services'])

<h2 id="contact-form-heading" class="text-h3">{{ __('Send an enquiry') }}</h2>

@if ($errors->any())
    <x-ui.alert variant="problem" :title="__('Please check the form')" class="mt-5">
        <ul class="flex flex-col gap-2">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </x-ui.alert>
@endif

<form method="POST" action="{{ route('quote.store') }}" class="mt-6 flex flex-col gap-6" x-data="{ submitting: false }" x-on:submit="submitting = true" x-bind:aria-busy="submitting">
    @csrf
    <div class="hidden" aria-hidden="true">
        <label for="website">{{ __('Leave this field empty') }}</label>
        <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <x-ui.field :label="__('Full name')" for="name" :error="$errors->first('name')">
            <x-ui.input id="name" name="name" :value="old('name', auth()->user()?->name)" :invalid="$errors->has('name')" :aria-describedby="$errors->has('name') ? 'name-error' : null" required maxlength="255" autocomplete="name" />
        </x-ui.field>
        <x-ui.field :label="__('Email address')" for="email" :error="$errors->first('email')">
            <x-ui.input id="email" name="email" type="email" :value="old('email', auth()->user()?->email)" :invalid="$errors->has('email')" :aria-describedby="$errors->has('email') ? 'email-error' : null" required maxlength="255" autocomplete="email" />
        </x-ui.field>
        <x-ui.field :label="__('Mobile or WhatsApp number')" for="phone" :error="$errors->first('phone')">
            <x-ui.input id="phone" name="phone" type="tel" :value="old('phone')" :invalid="$errors->has('phone')" :aria-describedby="$errors->has('phone') ? 'phone-error' : null" maxlength="50" autocomplete="tel" />
        </x-ui.field>
        <x-ui.field :label="__('Organisation name, if any')" for="organisation_name" :error="$errors->first('organisation_name')">
            <x-ui.input id="organisation_name" name="organisation_name" :value="old('organisation_name')" :invalid="$errors->has('organisation_name')" :aria-describedby="$errors->has('organisation_name') ? 'organisation_name-error' : null" maxlength="255" autocomplete="organization" />
        </x-ui.field>
    </div>

    <x-ui.field :label="__('Service')" for="service_id" :error="$errors->first('service_id')">
        <x-ui.select id="service_id" name="service_id" :aria-describedby="$errors->has('service_id') ? 'service_id-error' : null">
            <option value="">{{ __('I am not sure yet') }}</option>
            @foreach ($services as $categoryName => $group)
                <optgroup label="{{ $categoryName }}">
                    @foreach ($group as $service)
                        <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>{{ $service->name }}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </x-ui.select>
    </x-ui.field>

    <x-ui.field :label="__('What would you like help with?')" for="message" :error="$errors->first('message')" :hint="__('Do not include passwords, banking PINs or unnecessary identity documents.')">
        <x-ui.textarea id="message" name="message" rows="5" maxlength="2000" :invalid="$errors->has('message')" :aria-describedby="$errors->has('message') ? 'message-error' : 'message-hint'">{{ old('message') }}</x-ui.textarea>
    </x-ui.field>

    <x-ui.field :legend="__('Preferred contact channel')" :error="$errors->first('preferred_channel')">
        <div class="flex flex-wrap gap-2">
            @foreach (['whatsapp' => __('WhatsApp'), 'email' => __('Email'), 'phone' => __('Phone call')] as $value => $label)
                <x-ui.choice name="preferred_channel" :value="$value" :checked="old('preferred_channel', 'email') === $value" :required="$loop->first">{{ $label }}</x-ui.choice>
            @endforeach
        </div>
    </x-ui.field>

    <x-ui.field :error="$errors->first('consent')">
        <x-ui.choice type="checkbox" name="consent" value="1" :checked="(bool) old('consent')" required>
            {{ __('I agree that ITM Business Solutions may contact me about this request and store the information I have provided.') }}
        </x-ui.choice>
    </x-ui.field>

    <x-public.marketing-opt-in />

    <div class="flex flex-wrap items-center gap-4">
        <x-ui.button type="submit" icon="paper-plane-tilt" x-bind:disabled="submitting"><span x-show="!submitting">{{ __('Send enquiry') }}</span><span x-cloak x-show="submitting">{{ __('Sending...') }}</span></x-ui.button>
        <a href="{{ route('page', 'privacy') }}" class="text-meta">{{ __('Privacy Policy') }}</a>
    </div>
</form>
