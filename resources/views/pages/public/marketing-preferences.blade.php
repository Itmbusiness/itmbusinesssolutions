<x-layouts::public :title="__('Email preferences')" :indexable="false">
    <x-public.page-header
        :eyebrow="__('Email preferences')"
        :heading="__('Choose which updates you receive')"
        :lead="__('This choice concerns optional marketing emails from ITM Business Solutions. It does not affect messages about a request you have made.')"
    />

    <div class="page-shell section-tight max-w-3xl">
        @if (session('status'))
            <x-ui.alert variant="success" :title="__('Preference saved')" class="mb-6">
                {{ session('status') }}
            </x-ui.alert>
        @endif

        <x-ui.card>
            @if ($subscription->revoked_at)
                <h2 class="text-h3">{{ __('Marketing emails stopped') }}</h2>
                <p class="card-body mt-3">{{ __('ITM Business Solutions will not send marketing emails to this address. Messages about an existing request may still be sent when needed.') }}</p>
            @elseif ($subscription->isActive())
                <h2 class="text-h3">{{ __('Email updates confirmed') }}</h2>
                <p class="card-body mt-3">{{ __('You can stop optional guidance and service news at any time.') }}</p>
                <form method="POST" action="{{ route('marketing.preferences.unsubscribe', $token) }}" class="mt-6">
                    @csrf
                    <x-ui.button type="submit" variant="secondary">{{ __('Unsubscribe from marketing') }}</x-ui.button>
                </form>
            @else
                <h2 class="text-h3">{{ __('Confirm your choice') }}</h2>
                <p class="card-body mt-3">{{ __('Confirm if you would like occasional email guidance and service news from ITM Business Solutions. No marketing emails will be sent unless you confirm.') }}</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <form method="POST" action="{{ route('marketing.preferences.confirm', $token) }}">
                        @csrf
                        <x-ui.button type="submit">{{ __('Confirm email updates') }}</x-ui.button>
                    </form>
                    <form method="POST" action="{{ route('marketing.preferences.unsubscribe', $token) }}">
                        @csrf
                        <x-ui.button type="submit" variant="secondary">{{ __('No thanks') }}</x-ui.button>
                    </form>
                </div>
            @endif
        </x-ui.card>
    </div>
</x-layouts::public>
