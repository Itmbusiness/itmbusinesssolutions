<x-layouts::admin :title="__('Categories')">
    <x-admin.header :title="__('Categories')">
        <x-ui.button href="{{ route('admin.categories.create') }}" icon="plus">{{ __('New category') }}</x-ui.button>
    </x-admin.header>

    <x-ui.table class="mt-6" :caption="__('Service categories')">
        <thead>
            <tr>
                <th scope="col">{{ __('Name') }}</th>
                <th scope="col">{{ __('Services') }}</th>
                <th scope="col">{{ __('Status') }}</th>
                <th scope="col">{{ __('Order') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <th scope="row" class="font-normal">
                        <a href="{{ route('admin.categories.edit', $category) }}">{{ $category->name }}</a>
                        <span class="block card-meta font-mono">/services/category/{{ $category->slug }}</span>
                    </th>
                    <td class="text-muted">{{ $category->services_count }}</td>
                    <td>
                        <x-ui.badge :variant="$category->is_published ? 'success' : 'neutral'">
                            {{ $category->is_published ? __('Published') : __('Draft') }}
                        </x-ui.badge>
                    </td>
                    <td class="text-muted">{{ $category->position }}</td>
                </tr>
            @endforeach
        </tbody>
    </x-ui.table>
</x-layouts::admin>
