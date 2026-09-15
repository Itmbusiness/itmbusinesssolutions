@php $editing = $category->exists; @endphp

<x-layouts::admin :title="$editing ? $category->name : __('New category')">
    <x-admin.header
        :title="$editing ? $category->name : __('New category')"
        :back="route('admin.categories.index')"
        :back-label="__('Categories')"
    />

    <form method="POST" action="{{ $editing ? route('admin.categories.update', $category) : route('admin.categories.store') }}" class="mt-6">
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
                            <x-ui.input id="name" name="name" :value="old('name', $category->name)" required />
                        </x-ui.field>
                        <x-ui.field :label="__('URL slug')" for="slug" :error="$errors->first('slug')"
                            hint="{{ __('Leave blank to generate from the name.') }}">
                            <x-ui.input id="slug" name="slug" :value="old('slug', $category->slug)" />
                        </x-ui.field>
                        <x-ui.field :label="__('Summary')" for="summary" :error="$errors->first('summary')">
                            <x-ui.textarea id="summary" name="summary">{{ old('summary', $category->summary) }}</x-ui.textarea>
                        </x-ui.field>
                        <x-ui.field :label="__('Who this category helps')" for="audience"
                            hint="{{ __('One per line.') }}">
                            <x-ui.textarea id="audience" name="audience">{{ old('audience', filled($category->audience) ? implode("\n", $category->audience) : '') }}</x-ui.textarea>
                        </x-ui.field>
                        <x-ui.field :label="__('How the quotation process works')" for="body"
                            hint="{{ __('Markdown is supported.') }}">
                            <x-ui.textarea id="body" name="body">{{ old('body', $category->body) }}</x-ui.textarea>
                        </x-ui.field>
                    </div>
                </x-ui.card>
            </div>

            <aside class="flex flex-col gap-4">
                <x-ui.card class="elev-md">
                    <h2 class="card-title">{{ __('Publishing') }}</h2>
                    <div class="mt-4 flex flex-col gap-4">
                        <x-ui.choice type="checkbox" name="is_published" value="1" :checked="(bool) old('is_published', $category->is_published)">
                            {{ __('Published') }}
                        </x-ui.choice>
                        <x-ui.field :label="__('Icon')" for="icon"
                            hint="{{ __('A Phosphor icon name, such as buildings or receipt.') }}">
                            <x-ui.input id="icon" name="icon" :value="old('icon', $category->icon)" />
                        </x-ui.field>
                        <x-ui.field :label="__('Order')" for="position">
                            <x-ui.input id="position" name="position" type="number" min="0" :value="old('position', $category->position ?? 0)" />
                        </x-ui.field>
                        <x-ui.button type="submit" class="btn-block">
                            {{ $editing ? __('Save category') : __('Create category') }}
                        </x-ui.button>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <h2 class="card-title">{{ __('Search engines') }}</h2>
                    <div class="mt-4 flex flex-col gap-5">
                        <x-ui.field :label="__('SEO title')" for="seo_title">
                            <x-ui.input id="seo_title" name="seo_title" :value="old('seo_title', $category->seo_title)" />
                        </x-ui.field>
                        <x-ui.field :label="__('Meta description')" for="meta_description">
                            <x-ui.textarea id="meta_description" name="meta_description">{{ old('meta_description', $category->meta_description) }}</x-ui.textarea>
                        </x-ui.field>
                        <x-ui.field :label="__('Primary keyword')" for="primary_keyword">
                            <x-ui.input id="primary_keyword" name="primary_keyword" :value="old('primary_keyword', $category->primary_keyword)" />
                        </x-ui.field>
                    </div>
                </x-ui.card>
            </aside>
        </div>
    </form>

    @if ($editing)
        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="mt-4"
            onsubmit="return confirm('{{ __('Delete this category?') }}')">
            @csrf
            @method('DELETE')
            <x-ui.button type="submit" variant="quiet">{{ __('Delete category') }}</x-ui.button>
        </form>
    @endif
</x-layouts::admin>
