<x-layouts::admin :title="__('Pages')">
    <x-admin.header :title="__('Pages')" />

    <p class="card-body mt-2 max-w-[60ch]">
        {{ __('These pages have fixed URLs and templates. You can edit their wording and search-engine settings, but they cannot be created or deleted here.') }}
    </p>

    <x-ui.table class="mt-6" :caption="__('Editable pages')">
        <thead>
            <tr>
                <th scope="col">{{ __('Page') }}</th>
                <th scope="col">{{ __('URL') }}</th>
                <th scope="col">{{ __('Status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pages as $page)
                <tr>
                    <th scope="row" class="font-normal">
                        <a href="{{ route('admin.pages.edit', $page) }}">{{ $page->title }}</a>
                    </th>
                    <td class="font-mono text-muted">/{{ $page->slug }}</td>
                    <td class="flex flex-wrap gap-1.5">
                        <x-ui.badge :variant="$page->is_published ? 'success' : 'neutral'">
                            {{ $page->is_published ? __('Published') : __('Hidden') }}
                        </x-ui.badge>
                        @unless ($page->is_indexable)
                            <x-ui.badge variant="neutral">{{ __('Noindex') }}</x-ui.badge>
                        @endunless
                    </td>
                </tr>
            @endforeach
        </tbody>
    </x-ui.table>
</x-layouts::admin>
