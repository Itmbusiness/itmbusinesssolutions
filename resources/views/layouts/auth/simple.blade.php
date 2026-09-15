<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-page antialiased">
        <div class="flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-md flex-col gap-6">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-3 no-underline hover:no-underline" wire:navigate>
                    <img
                        src="{{ asset('images/itm-logo.png') }}"
                        alt=""
                        class="size-14 rounded-control object-cover"
                    >
                    <span class="text-h3 text-navy">{{ config('app.name', 'Laravel') }}</span>
                    <span class="sr-only">{{ __('Home') }}</span>
                </a>

                <x-ui.card class="flex flex-col gap-6">
                    {{ $slot }}
                </x-ui.card>

                <p class="text-center text-meta text-muted">
                    ITM will never ask for your password, banking PIN or a one-time code.
                </p>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
