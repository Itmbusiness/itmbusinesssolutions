<x-layouts::auth :title="__('Log in')">
    <x-auth-header
        :title="__('Log in to your account')"
        :description="__('Enter your email and password below to log in')"
    />

    <!-- Session Status -->
    <x-auth-session-status :status="session('status')" />

    {{-- @chisel-passkeys --}}
    <x-passkey-verify />
    {{-- @end-chisel-passkeys --}}

    <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
        @csrf

        <x-ui.field
            :label="__('Email address')"
            for="email"
            :error="$errors->first('email')"
        >
            <x-ui.input
                id="email"
                name="email"
                type="email"
                :value="old('email')"
                :invalid="$errors->has('email')"
                :aria-describedby="$errors->has('email') ? 'email-error' : null"
                required
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
            />
        </x-ui.field>

        <x-ui.field
            :label="__('Password')"
            for="password"
            :error="$errors->first('password')"
        >
            <x-ui.input
                id="password"
                name="password"
                type="password"
                :invalid="$errors->has('password')"
                :aria-describedby="$errors->has('password') ? 'password-error' : null"
                required
                autocomplete="current-password"
            />
        </x-ui.field>

        @if (Route::has('password.request'))
            <a class="-mt-2 text-meta" href="{{ route('password.request') }}" wire:navigate>
                {{ __('Forgot your password?') }}
            </a>
        @endif

        {{-- The choice tile sizes to its content rather than spanning the form. --}}
        <div class="flex">
            <x-ui.choice type="checkbox" name="remember" value="1" :checked="(bool) old('remember')">
                {{ __('Remember me') }}
            </x-ui.choice>
        </div>

        <x-ui.button type="submit" class="btn-block" data-test="login-button">
            {{ __('Log in') }}
        </x-ui.button>
    </form>

    {{-- @chisel-registration --}}
    <p class="text-center text-meta text-muted">
        {{ __('Don\'t have an account?') }}
        <a href="{{ route('register') }}" wire:navigate>{{ __('Sign up') }}</a>
    </p>
    {{-- @end-chisel-registration --}}
</x-layouts::auth>
