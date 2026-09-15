@php $editing = $package->exists; @endphp

<x-layouts::admin :title="$editing ? $package->name : __('New package')">
    <x-admin.header
        :title="$editing ? $package->name : __('New package')"
        :back="route('admin.packages.index')"
        :back-label="__('Packages')"
    />

    <form method="POST" action="{{ $editing ? route('admin.packages.update', $package) : route('admin.packages.store') }}" class="mt-6">
        @csrf
        @if ($editing)
            @method('PUT')
        @endif

        <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_320px]">
            <x-ui.card>
                <div class="flex flex-col gap-5">
                    <x-ui.field :label="__('Name')" for="name" :error="$errors->first('name')">
                        <x-ui.input id="name" name="name" :value="old('name', $package->name)" required />
                    </x-ui.field>
                    <x-ui.field :label="__('URL slug')" for="slug" :error="$errors->first('slug')"
                        hint="{{ __('Leave blank to generate from the name.') }}">
                        <x-ui.input id="slug" name="slug" :value="old('slug', $package->slug)" />
                    </x-ui.field>
                    <x-ui.field :label="__('Summary')" for="summary" :error="$errors->first('summary')">
                        <x-ui.textarea id="summary" name="summary">{{ old('summary', $package->summary) }}</x-ui.textarea>
                    </x-ui.field>
                    <x-ui.field :label="__('What it includes')" for="includes" hint="{{ __('One item per line.') }}">
                        <x-ui.textarea id="includes" name="includes">{{ old('includes', filled($package->includes) ? implode("\n", $package->includes) : '') }}</x-ui.textarea>
                    </x-ui.field>
                    <x-ui.field :label="__('Body')" for="body" hint="{{ __('Optional. Markdown is supported.') }}">
                        <x-ui.textarea id="body" name="body">{{ old('body', $package->body) }}</x-ui.textarea>
                    </x-ui.field>
                </div>
            </x-ui.card>

            <aside>
                <x-ui.card class="elev-md">
                    <h2 class="card-title">{{ __('Publishing') }}</h2>
                    <div class="mt-4 flex flex-col gap-4">
                        <x-ui.choice type="checkbox" name="is_published" value="1" :checked="(bool) old('is_published', $package->is_published)">
                            {{ __('Published') }}
                        </x-ui.choice>
                        <x-ui.field :label="__('Order')" for="position">
                            <x-ui.input id="position" name="position" type="number" min="0" :value="old('position', $package->position ?? 0)" />
                        </x-ui.field>
                        <x-ui.button type="submit" class="btn-block">
                            {{ $editing ? __('Save package') : __('Create package') }}
                        </x-ui.button>
                    </div>
                </x-ui.card>
            </aside>
        </div>
    </form>

    @if ($editing)
        <form method="POST" action="{{ route('admin.packages.destroy', $package) }}" class="mt-4"
            onsubmit="return confirm('{{ __('Delete this package?') }}')">
            @csrf
            @method('DELETE')
            <x-ui.button type="submit" variant="quiet">{{ __('Delete package') }}</x-ui.button>
        </form>
    @endif
</x-layouts::admin>
