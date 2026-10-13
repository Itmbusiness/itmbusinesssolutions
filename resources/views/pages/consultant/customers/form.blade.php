@php $editing = $customer->exists; @endphp

<x-layouts::app :title="$editing ? $customer->name : __('New customer')">
    <div class="page-shell py-8">
        <header class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-eyebrow uppercase text-teal">{{ __('Consultant') }}</p>
                <h1 class="mt-2 text-h2">{{ $editing ? $customer->name : __('New customer') }}</h1>
            </div>

            <x-ui.button variant="secondary" href="{{ $editing ? route('consultant.customers.show', $customer) : route('consultant.customers.index') }}">
                {{ __('Cancel') }}
            </x-ui.button>
        </header>

        <form method="POST" action="{{ $editing ? route('consultant.customers.update', $customer) : route('consultant.customers.store') }}" class="mt-6 max-w-160">
            @csrf
            @if ($editing)
                @method('PUT')
            @endif

            <x-ui.card>
                <div class="flex flex-col gap-5">
                    <x-ui.field :label="__('Name')" for="name" :error="$errors->first('name')">
                        <x-ui.input id="name" name="name" :value="old('name', $customer->name)" required />
                    </x-ui.field>
                    <x-ui.field :label="__('Email')" for="email" :error="$errors->first('email')">
                        <x-ui.input id="email" name="email" type="email" :value="old('email', $customer->email)" />
                    </x-ui.field>
                    <x-ui.field :label="__('Phone')" for="phone" :error="$errors->first('phone')">
                        <x-ui.input id="phone" name="phone" :value="old('phone', $customer->phone)" />
                    </x-ui.field>
                    <x-ui.field :label="__('Stage')" for="stage" :error="$errors->first('stage')">
                        <x-ui.select id="stage" name="stage">
                            @foreach ($stages as $stage)
                                <option value="{{ $stage->value }}" @selected(old('stage', $customer->stage->value) === $stage->value)>
                                    {{ $stage->label() }}
                                </option>
                            @endforeach
                        </x-ui.select>
                    </x-ui.field>
                </div>
            </x-ui.card>

            <x-ui.button type="submit" class="mt-4">
                {{ $editing ? __('Save customer') : __('Add customer') }}
            </x-ui.button>
        </form>

        @if ($editing)
            <form method="POST" action="{{ route('consultant.customers.destroy', $customer) }}" class="mt-4"
                onsubmit="return confirm('{{ __('Delete this customer? Their notes and tasks will be removed too.') }}')">
                @csrf
                @method('DELETE')
                <x-ui.button type="submit" variant="quiet">{{ __('Delete customer') }}</x-ui.button>
            </form>
        @endif
    </div>
</x-layouts::app>
