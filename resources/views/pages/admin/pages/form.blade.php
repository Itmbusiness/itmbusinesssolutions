<x-layouts::admin :title="$page->title">
    <x-admin.header :title="$page->title" :back="route('admin.pages.index')" :back-label="__('Pages')">
        <x-ui.button variant="quiet" href="{{ url('/'.$page->slug) }}" target="_blank" rel="noopener">
            {{ __('View page') }}
        </x-ui.button>
    </x-admin.header>

    <form method="POST" action="{{ route('admin.pages.update', $page) }}" class="mt-6">
        @csrf
        @method('PUT')

        <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_320px]">
            <div class="flex flex-col gap-4">
                <x-ui.card>
                    <x-ui.field :label="__('Title')" for="title" :error="$errors->first('title')">
                        <x-ui.input id="title" name="title" :value="old('title', $page->title)" required />
                    </x-ui.field>
                </x-ui.card>

                <x-ui.card>
                    <h2 class="card-title">{{ __('Body') }}</h2>
                    <p class="card-body mt-1">{{ __('Markdown is supported. The FAQ page renders its questions from the FAQ section instead of this body.') }}</p>
                    <x-ui.field class="mt-4" for="body" :error="$errors->first('body')">
                        <x-ui.textarea id="body" name="body" rows="24" class="min-h-[28rem] font-mono text-meta">{{ old('body', $page->body) }}</x-ui.textarea>
                    </x-ui.field>
                </x-ui.card>
            </div>

            <aside class="flex flex-col gap-4">
                <x-ui.card class="elev-md">
                    <h2 class="card-title">{{ __('Visibility') }}</h2>
                    <div class="mt-4 flex flex-col gap-4">
                        <x-ui.choice type="checkbox" name="is_published" value="1" :checked="(bool) old('is_published', $page->is_published)">
                            {{ __('Published') }}
                        </x-ui.choice>
                        <x-ui.choice type="checkbox" name="is_indexable" value="1" :checked="(bool) old('is_indexable', $page->is_indexable)">
                            {{ __('Allow search engines') }}
                        </x-ui.choice>
                        <x-ui.button type="submit" class="btn-block">{{ __('Save page') }}</x-ui.button>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <h2 class="card-title">{{ __('Search engines') }}</h2>
                    <div class="mt-4 flex flex-col gap-5">
                        <x-ui.field :label="__('SEO title')" for="seo_title">
                            <x-ui.input id="seo_title" name="seo_title" :value="old('seo_title', $page->seo_title)" />
                        </x-ui.field>
                        <x-ui.field :label="__('Meta description')" for="meta_description">
                            <x-ui.textarea id="meta_description" name="meta_description">{{ old('meta_description', $page->meta_description) }}</x-ui.textarea>
                        </x-ui.field>
                    </div>
                </x-ui.card>
            </aside>
        </div>
    </form>
</x-layouts::admin>
