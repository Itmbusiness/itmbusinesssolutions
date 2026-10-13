@php $editing = $article->exists; @endphp

<x-layouts::admin :title="$editing ? $article->title : __('New article')">
    <x-admin.header
        :title="$editing ? $article->title : __('New article')"
        :back="route('admin.articles.index')"
        :back-label="__('Articles')"
    >
        @if ($editing && $article->is_published)
            <x-ui.button variant="quiet" href="{{ route('articles.show', $article) }}" target="_blank" rel="noopener">
                {{ __('View page') }}
            </x-ui.button>
        @endif
    </x-admin.header>

    <form method="POST" action="{{ $editing ? route('admin.articles.update', $article) : route('admin.articles.store') }}" class="mt-6">
        @csrf
        @if ($editing)
            @method('PUT')
        @endif

        <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_320px]">
            <div class="flex flex-col gap-4">
                <x-ui.card>
                    <div class="flex flex-col gap-5">
                        <x-ui.field :label="__('Title')" for="title" :error="$errors->first('title')">
                            <x-ui.input id="title" name="title" :value="old('title', $article->title)" required />
                        </x-ui.field>
                        <x-ui.field :label="__('URL slug')" for="slug" :error="$errors->first('slug')"
                            hint="{{ __('Leave blank to generate from the title.') }}">
                            <x-ui.input id="slug" name="slug" :value="old('slug', $article->slug)" />
                        </x-ui.field>
                        <x-ui.field :label="__('Excerpt')" for="excerpt" :error="$errors->first('excerpt')">
                            <x-ui.textarea id="excerpt" name="excerpt">{{ old('excerpt', $article->excerpt) }}</x-ui.textarea>
                        </x-ui.field>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <h2 class="card-title">{{ __('Body') }}</h2>
                    <p class="card-body mt-1">{{ __('Markdown is supported — headings, lists, links and tables.') }}</p>
                    <x-ui.field class="mt-4" for="body" :error="$errors->first('body')">
                        <x-ui.textarea id="body" name="body" rows="24" class="min-h-112 font-mono text-meta">{{ old('body', $article->body) }}</x-ui.textarea>
                    </x-ui.field>
                </x-ui.card>

                <x-ui.card>
                    <h2 class="card-title">{{ __('In-article call to action') }}</h2>
                    <p class="card-body mt-1">{{ __('Optional. Renders as a button between the article and its author/reviewer byline. Leave both blank to omit it.') }}</p>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <x-ui.field :label="__('Button label')" for="cta_label" :error="$errors->first('cta_label')">
                            <x-ui.input id="cta_label" name="cta_label" :value="old('cta_label', $article->cta_label)" />
                        </x-ui.field>
                        <x-ui.field :label="__('Link')" for="cta_url" hint="{{ __('An absolute URL, or a path such as /services.') }}" :error="$errors->first('cta_url')">
                            <x-ui.input id="cta_url" name="cta_url" :value="old('cta_url', $article->cta_url)" />
                        </x-ui.field>
                    </div>
                </x-ui.card>
            </div>

            <aside class="flex flex-col gap-4">
                <x-ui.card class="elev-md">
                    <h2 class="card-title">{{ __('Publishing') }}</h2>
                    <div class="mt-4 flex flex-col gap-4">
                        <x-ui.choice type="checkbox" name="is_published" value="1" :checked="(bool) old('is_published', $article->is_published)">
                            {{ __('Published') }}
                        </x-ui.choice>
                        <x-ui.field :label="__('Category')" for="service_category_id">
                            <x-ui.select id="service_category_id" name="service_category_id">
                                <option value="">{{ __('Uncategorised') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('service_category_id', $article->service_category_id) == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </x-ui.select>
                        </x-ui.field>
                        <x-ui.field :label="__('Order')" for="position">
                            <x-ui.input id="position" name="position" type="number" min="0" :value="old('position', $article->position ?? 0)" />
                        </x-ui.field>
                        <x-ui.button type="submit" class="btn-block">
                            {{ $editing ? __('Save article') : __('Create article') }}
                        </x-ui.button>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <h2 class="card-title">{{ __('Authorship & review') }}</h2>
                    <div class="mt-4 flex flex-col gap-5">
                        <x-ui.field :label="__('Author')" for="author">
                            <x-ui.input id="author" name="author" :value="old('author', $article->author)" />
                        </x-ui.field>
                        <x-ui.field :label="__('Reviewer')" for="reviewer">
                            <x-ui.input id="reviewer" name="reviewer" :value="old('reviewer', $article->reviewer)" />
                        </x-ui.field>
                        <x-ui.field :label="__('Reviewed on')" for="reviewed_on">
                            <x-ui.input id="reviewed_on" name="reviewed_on" type="date" :value="old('reviewed_on', $article->reviewed_on?->format('Y-m-d'))" />
                        </x-ui.field>
                        <x-ui.field :label="__('Next review')" for="next_review_date">
                            <x-ui.input id="next_review_date" name="next_review_date" type="date" :value="old('next_review_date', $article->next_review_date?->format('Y-m-d'))" />
                        </x-ui.field>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <h2 class="card-title">{{ __('Search engines') }}</h2>
                    <div class="mt-4 flex flex-col gap-5">
                        <x-ui.field :label="__('SEO title')" for="seo_title">
                            <x-ui.input id="seo_title" name="seo_title" :value="old('seo_title', $article->seo_title)" />
                        </x-ui.field>
                        <x-ui.field :label="__('Meta description')" for="meta_description">
                            <x-ui.textarea id="meta_description" name="meta_description">{{ old('meta_description', $article->meta_description) }}</x-ui.textarea>
                        </x-ui.field>
                        <x-ui.field :label="__('Primary keyword')" for="primary_keyword">
                            <x-ui.input id="primary_keyword" name="primary_keyword" :value="old('primary_keyword', $article->primary_keyword)" />
                        </x-ui.field>
                    </div>
                </x-ui.card>
            </aside>
        </div>
    </form>

    @if ($editing)
        <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" class="mt-4"
            onsubmit="return confirm('{{ __('Delete this article permanently?') }}')">
            @csrf
            @method('DELETE')
            <x-ui.button type="submit" variant="quiet">{{ __('Delete article') }}</x-ui.button>
        </form>
    @endif
</x-layouts::admin>
