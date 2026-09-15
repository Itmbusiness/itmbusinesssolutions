<x-layouts::admin :title="__('Articles')">
    <x-admin.header :title="__('Articles')">
        <form method="GET" action="{{ route('admin.articles.index') }}" class="flex items-end gap-2">
            <x-ui.field :label="__('Search')" for="q" class="w-full sm:w-56">
                <x-ui.input id="q" name="q" :value="request('q')" />
            </x-ui.field>
            <x-ui.button variant="secondary" type="submit">{{ __('Search') }}</x-ui.button>
        </form>
        <x-ui.button href="{{ route('admin.articles.create') }}" icon="plus">{{ __('New article') }}</x-ui.button>
    </x-admin.header>

    <x-ui.table class="mt-6" :caption="__('Articles')">
        <thead>
            <tr>
                <th scope="col">{{ __('Title') }}</th>
                <th scope="col">{{ __('Category') }}</th>
                <th scope="col">{{ __('Status') }}</th>
                <th scope="col">{{ __('Review') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($articles as $article)
                <tr>
                    <th scope="row" class="font-normal">
                        <a href="{{ route('admin.articles.edit', $article) }}">{{ $article->title }}</a>
                        <span class="block card-meta font-mono">/articles/{{ $article->slug }}</span>
                    </th>
                    <td class="text-muted">{{ $article->category?->name ?? '—' }}</td>
                    <td>
                        <x-ui.badge :variant="$article->is_published ? 'success' : 'neutral'">
                            {{ $article->is_published ? __('Published') : __('Draft') }}
                        </x-ui.badge>
                    </td>
                    <td>
                        @if ($article->isStale())
                            <x-ui.badge variant="attention">{{ __('Due review') }}</x-ui.badge>
                        @else
                            <span class="text-muted">{{ $article->next_review_date?->format('j M Y') ?? '—' }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </x-ui.table>

    <div class="mt-6">{{ $articles->links() }}</div>
</x-layouts::admin>
