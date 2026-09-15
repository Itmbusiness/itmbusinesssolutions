@php $editing = $faq->exists; @endphp

<x-layouts::admin :title="$editing ? __('Edit FAQ') : __('New FAQ')">
    <x-admin.header
        :title="$editing ? __('Edit FAQ') : __('New FAQ')"
        :back="route('admin.faqs.index')"
        :back-label="__('FAQs')"
    />

    <form method="POST" action="{{ $editing ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}" class="mt-6">
        @csrf
        @if ($editing)
            @method('PUT')
        @endif

        <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_320px]">
            <x-ui.card>
                <div class="flex flex-col gap-5">
                    <x-ui.field :label="__('Question')" for="question" :error="$errors->first('question')">
                        <x-ui.input id="question" name="question" :value="old('question', $faq->question)" required />
                    </x-ui.field>
                    <x-ui.field :label="__('Answer')" for="answer" :error="$errors->first('answer')">
                        <x-ui.textarea id="answer" name="answer" rows="8" required>{{ old('answer', $faq->answer) }}</x-ui.textarea>
                    </x-ui.field>
                </div>
            </x-ui.card>

            <aside>
                <x-ui.card class="elev-md">
                    <h2 class="card-title">{{ __('Placement') }}</h2>
                    <div class="mt-4 flex flex-col gap-4">
                        <x-ui.field :label="__('Attach to service')" for="service_id"
                            hint="{{ __('Leave blank to show it on the site-wide FAQ page.') }}">
                            <x-ui.select id="service_id" name="service_id">
                                <option value="">{{ __('Site-wide FAQ page') }}</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}" @selected(old('service_id', $faq->faqable_id) == $service->id)>
                                        {{ $service->name }}
                                    </option>
                                @endforeach
                            </x-ui.select>
                        </x-ui.field>

                        <x-ui.choice type="checkbox" name="is_published" value="1" :checked="(bool) old('is_published', $faq->is_published)">
                            {{ __('Published') }}
                        </x-ui.choice>

                        <x-ui.field :label="__('Order')" for="position">
                            <x-ui.input id="position" name="position" type="number" min="0" :value="old('position', $faq->position ?? 0)" />
                        </x-ui.field>

                        <x-ui.button type="submit" class="btn-block">
                            {{ $editing ? __('Save FAQ') : __('Create FAQ') }}
                        </x-ui.button>
                    </div>
                </x-ui.card>
            </aside>
        </div>
    </form>

    @if ($editing)
        <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" class="mt-4"
            onsubmit="return confirm('{{ __('Delete this FAQ?') }}')">
            @csrf
            @method('DELETE')
            <x-ui.button type="submit" variant="quiet">{{ __('Delete FAQ') }}</x-ui.button>
        </form>
    @endif
</x-layouts::admin>
