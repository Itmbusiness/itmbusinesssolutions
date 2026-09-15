<x-layouts::auth :title="__('Forgot password')">
    <x-auth-header
        :title="__('Forgot password')"
        :description="__('Enter your email to receive a password reset link')"
    />

    <!-- Session Status -->
    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-5">
        @csrf

        <x-ui.field :label="__('Email address')" for="email" :error="$errors->first('email')">
            <x-ui.input
                id="email"
                name="email"
                type="email"
                :value="old('email')"
                :invalid="$errors->has('email')"
                :aria-describedby="$errors->has('email') ? 'email-error' : null"
                required
                autofocus
                placeholder="email@example.com"
            />
        </x-ui.field>

        <x-ui.button type="submit" class="btn-block" data-test="email-password-reset-link-button">
            {{ __('Email password reset link') }}
        </x-ui.button>
    </form>

    <p class="text-center text-meta text-muted">
        {{ __('Or, return to') }}
        <a href="{{ route('login') }}" wire:navigate>{{ __('log in') }}</a>
    </p>
</x-layouts::auth>
