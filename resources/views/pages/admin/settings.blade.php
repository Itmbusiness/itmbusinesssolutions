<x-layouts::admin :title="__('Settings')">
    <x-admin.header :title="__('Settings')" />

    <p class="card-body mt-2 max-w-[60ch]">
        {{ __('These values appear across the public site — in the top bar, the footer and on forms. Changing them here updates every page immediately.') }}
    </p>

    <section class="mt-8" aria-labelledby="public-images-heading">
        <h2 id="public-images-heading" class="text-h3">{{ __('Public Images') }}</h2>
        <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($imageSubjects as $subject => $label)
                <x-admin.public-image :subject="$subject" :label="$label" :custom="filled($values['media.'.$subject] ?? null)" />
            @endforeach
        </div>
    </section>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="mt-10">
        @csrf
        @method('PUT')

        <div class="grid gap-4 lg:grid-cols-2">
        @foreach (collect($fields)->groupBy(fn ($field) => $field[1]) as $group => $groupFields)
            <x-ui.card class="mb-4">
                <h2 class="card-title">{{ Str::headline($group) }}</h2>

                <div class="mt-4 flex flex-col gap-5">
                    @foreach ($fields as $key => [$label, $fieldGroup, $hint])
                        @continue($fieldGroup !== $group)

                        <x-ui.field :label="$label" :for="'setting-'.Str::slug($key)" :hint="$hint">
                            <x-ui.input
                                :id="'setting-'.Str::slug($key)"
                                name="settings[{{ $key }}]"
                                :value="old('settings.'.$key, $values[$key] ?? '')"
                            />
                        </x-ui.field>
                    @endforeach
                </div>
            </x-ui.card>
        @endforeach
        </div>

        <x-ui.button type="submit">{{ __('Save settings') }}</x-ui.button>
    </form>
</x-layouts::admin>
