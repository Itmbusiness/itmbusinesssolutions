<x-layouts::auth :title="__('Register')">
    <x-auth-header
        :title="__('Create an account')"
        :description="__('Enter your details below to create your account')"
    />

    <!-- Session Status -->
    <x-auth-session-status :status="session('status')" />

    @php
        $passwordRules = \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString();
    @endphp

    <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-5">
        @csrf

        <x-ui.field legend="{{ __('I am registering as') }}" :error="$errors->first('role')">
            <div class="flex flex-wrap gap-2">
                <x-ui.choice name="role" value="client" :checked="old('role', 'client') === 'client'">
                    {{ __('Client') }}
                </x-ui.choice>
                <x-ui.choice name="role" value="consultant" :checked="old('role') === 'consultant'">
                    {{ __('Consultant or agent') }}
                </x-ui.choice>
            </div>
        </x-ui.field>

        <x-ui.field :label="__('Name')" for="name" :error="$errors->first('name')">
            <x-ui.input
                id="name"
                name="name"
                type="text"
                :value="old('name')"
                :invalid="$errors->has('name')"
                :aria-describedby="$errors->has('name') ? 'name-error' : null"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('Full name')"
            />
        </x-ui.field>

        <x-ui.field :label="__('Email address')" for="email" :error="$errors->first('email')">
            <x-ui.input
                id="email"
                name="email"
                type="email"
                :value="old('email')"
                :invalid="$errors->has('email')"
                :aria-describedby="$errors->has('email') ? 'email-error' : null"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />
        </x-ui.field>

        <x-ui.field :label="__('Password')" for="password" :error="$errors->first('password')">
            <x-ui.input
                id="password"
                name="password"
                type="password"
                :invalid="$errors->has('password')"
                :aria-describedby="$errors->has('password') ? 'password-error' : null"
                required
                autocomplete="new-password"
                :passwordrules="$passwordRules"
            />
        </x-ui.field>

        <x-ui.field
            :label="__('Confirm password')"
            for="password_confirmation"
            :error="$errors->first('password_confirmation')"
        >
            <x-ui.input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                :invalid="$errors->has('password_confirmation')"
                required
                autocomplete="new-password"
                :passwordrules="$passwordRules"
            />
        </x-ui.field>

        <x-ui.button type="submit" class="btn-block" data-test="register-user-button">
            {{ __('Create account') }}
        </x-ui.button>
    </form>

    <p class="text-center text-meta text-muted">
        {{ __('Already have an account?') }}
        <a href="{{ route('login') }}" wire:navigate>{{ __('Log in') }}</a>
    </p>
</x-layouts::auth>
