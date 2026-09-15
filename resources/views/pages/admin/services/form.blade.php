@php
    $editing = $service->exists;
    $lines = fn (?array $items) => filled($items) ? implode("\n", $items) : '';
@endphp

<x-layouts::admin :title="$editing ? $service->name : __('New service')">
    <x-admin.header
        :title="$editing ? $service->name : __('New service')"
        :back="route('admin.services.index')"
        :back-label="__('Services')"
    >
        @if ($editing)
            <x-ui.button variant="quiet" href="{{ route('services.show', $service) }}" target="_blank" rel="noopener">
                {{ __('View page') }}
            </x-ui.button>
        @endif
    </x-admin.header>

    <form method="POST" action="{{ $editing ? route('admin.services.update', $service) : route('admin.services.store') }}" class="mt-6 flex flex-col gap-4">
        @csrf
        @if ($editing)
            @method('PUT')
        @endif

        <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_320px]">
            <div class="flex flex-col gap-4">
                <x-ui.card>
                    <h2 class="card-title">{{ __('Basics') }}</h2>
                    <div class="mt-4 flex flex-col gap-5">
                        <x-ui.field :label="__('Name')" for="name" :error="$errors->first('name')">
                            <x-ui.input id="name" name="name" :value="old('name', $service->name)" :invalid="$errors->has('name')" required />
                        </x-ui.field>

                        <x-ui.field :label="__('URL slug')" for="slug" :error="$errors->first('slug')"
                            hint="{{ __('Leave blank to generate from the name. Changing this changes the public URL.') }}">
                            <x-ui.input id="slug" name="slug" :value="old('slug', $service->slug)" :invalid="$errors->has('slug')" />
                        </x-ui.field>

                        <x-ui.field :label="__('Summary')" for="summary" :error="$errors->first('summary')"
                            hint="{{ __('One sentence, used on cards and in search results.') }}">
                            <x-ui.textarea id="summary" name="summary" :invalid="$errors->has('summary')">{{ old('summary', $service->summary) }}</x-ui.textarea>
                        </x-ui.field>

                        <x-ui.field :label="__('Intro')" for="intro" :error="$errors->first('intro')"
                            hint="{{ __('The opening paragraph on the service page.') }}">
                            <x-ui.textarea id="intro" name="intro" :invalid="$errors->has('intro')">{{ old('intro', $service->intro) }}</x-ui.textarea>
                        </x-ui.field>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <h2 class="card-title">{{ __('Page sections') }}</h2>
                    <p class="card-body mt-1">{{ __('One item per line. Leave a section empty to hide it.') }}</p>

                    <div class="mt-4 flex flex-col gap-5">
                        @foreach ([
                            'suited_to' => __('Is this service right for you?'),
                            'includes' => __('What the service can include'),
                            'requirements' => __('Starting requirements'),
                            'steps' => __('How it works'),
                        ] as $field => $label)
                            <x-ui.field :label="$label" :for="$field" :error="$errors->first($field)">
                                <x-ui.textarea :id="$field" :name="$field">{{ old($field, $lines($service->{$field})) }}</x-ui.textarea>
                            </x-ui.field>
                        @endforeach
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <h2 class="card-title">{{ __('Notes') }}</h2>
                    <div class="mt-4 flex flex-col gap-5">
                        <x-ui.field :label="__('Processing note')" for="processing_note">
                            <x-ui.textarea id="processing_note" name="processing_note">{{ old('processing_note', $service->processing_note) }}</x-ui.textarea>
                        </x-ui.field>
                        <x-ui.field :label="__('Source and review note')" for="source_note"
                            hint="{{ __('Internal reminder of what to verify before publishing.') }}">
                            <x-ui.textarea id="source_note" name="source_note">{{ old('source_note', $service->source_note) }}</x-ui.textarea>
                        </x-ui.field>
                    </div>
                </x-ui.card>
            </div>

            <aside class="flex flex-col gap-4">
                <x-ui.card class="elev-md">
                    <h2 class="card-title">{{ __('Publishing') }}</h2>
                    <div class="mt-4 flex flex-col gap-4">
                        <x-ui.choice type="checkbox" name="is_published" value="1" :checked="(bool) old('is_published', $service->is_published)">
                            {{ __('Published') }}
                        </x-ui.choice>
                        <x-ui.choice type="checkbox" name="is_featured" value="1" :checked="(bool) old('is_featured', $service->is_featured)">
                            {{ __('Feature on homepage') }}
                        </x-ui.choice>

                        <x-ui.field :label="__('Category')" for="service_category_id" :error="$errors->first('service_category_id')">
                            <x-ui.select id="service_category_id" name="service_category_id" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('service_category_id', $service->service_category_id) == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </x-ui.select>
                        </x-ui.field>

                        <x-ui.field :label="__('Order')" for="position" :error="$errors->first('position')">
                            <x-ui.input id="position" name="position" type="number" min="0" :value="old('position', $service->position ?? 0)" />
                        </x-ui.field>

                        <x-ui.button type="submit" class="btn-block">
                            {{ $editing ? __('Save service') : __('Create service') }}
                        </x-ui.button>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <h2 class="card-title">{{ __('Search engines') }}</h2>
                    <div class="mt-4 flex flex-col gap-5">
                        <x-ui.field :label="__('SEO title')" for="seo_title" :error="$errors->first('seo_title')">
                            <x-ui.input id="seo_title" name="seo_title" :value="old('seo_title', $service->seo_title)" />
                        </x-ui.field>
                        <x-ui.field :label="__('Meta description')" for="meta_description" :error="$errors->first('meta_description')">
                            <x-ui.textarea id="meta_description" name="meta_description">{{ old('meta_description', $service->meta_description) }}</x-ui.textarea>
                        </x-ui.field>
                        <x-ui.field :label="__('Primary keyword')" for="primary_keyword">
                            <x-ui.input id="primary_keyword" name="primary_keyword" :value="old('primary_keyword', $service->primary_keyword)" />
                        </x-ui.field>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <h2 class="card-title">{{ __('Review') }}</h2>
                    <p class="card-body mt-1">{{ __('Regulated pages show a notice once the review date passes.') }}</p>
                    <div class="mt-4 flex flex-col gap-5">
                        <x-ui.field :label="__('Reviewer')" for="reviewer">
                            <x-ui.input id="reviewer" name="reviewer" :value="old('reviewer', $service->reviewer)" />
                        </x-ui.field>
                        <x-ui.field :label="__('Reviewed on')" for="reviewed_on">
                            <x-ui.input id="reviewed_on" name="reviewed_on" type="date" :value="old('reviewed_on', $service->reviewed_on?->format('Y-m-d'))" />
                        </x-ui.field>
                        <x-ui.field :label="__('Next review')" for="next_review_date">
                            <x-ui.input id="next_review_date" name="next_review_date" type="date" :value="old('next_review_date', $service->next_review_date?->format('Y-m-d'))" />
                        </x-ui.field>
                    </div>
                </x-ui.card>
            </aside>
        </div>
    </form>

    @if ($editing)
        <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="mt-4"
            onsubmit="return confirm('{{ __('Delete this service permanently?') }}')">
            @csrf
            @method('DELETE')
            <x-ui.button type="submit" variant="quiet">{{ __('Delete service') }}</x-ui.button>
        </form>
    @endif
</x-layouts::admin>
