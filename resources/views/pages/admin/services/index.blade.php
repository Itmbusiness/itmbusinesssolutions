<x-layouts::admin :title="__('Services')">
    <x-admin.header :title="__('Services')">
        <form method="GET" action="{{ route('admin.services.index') }}" class="flex items-end gap-2">
            <x-ui.field :label="__('Search')" for="q" class="w-full sm:w-56">
                <x-ui.input id="q" name="q" :value="request('q')" />
            </x-ui.field>
            <x-ui.button variant="secondary" type="submit">{{ __('Search') }}</x-ui.button>
        </form>
        <x-ui.button href="{{ route('admin.services.create') }}" icon="plus">{{ __('New service') }}</x-ui.button>
    </x-admin.header>

    <x-ui.table class="mt-6" :caption="__('Services')">
        <thead>
            <tr>
                <th scope="col">{{ __('Name') }}</th>
                <th scope="col">{{ __('Category') }}</th>
                <th scope="col">{{ __('Status') }}</th>
                <th scope="col">{{ __('Leads') }}</th>
                <th scope="col"><span class="sr-only">{{ __('Actions') }}</span></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($services as $service)
                <tr>
                    <th scope="row" class="font-normal">
                        <a href="{{ route('admin.services.edit', $service) }}">{{ $service->name }}</a>
                        <span class="block card-meta font-mono">/services/{{ $service->slug }}</span>
                    </th>
                    <td class="text-muted">{{ $service->category?->name }}</td>
                    <td class="flex flex-wrap gap-1.5">
                        <x-ui.badge :variant="$service->is_published ? 'success' : 'neutral'">
                            {{ $service->is_published ? __('Published') : __('Draft') }}
                        </x-ui.badge>
                        @if ($service->is_featured)
                            <x-ui.badge variant="featured">{{ __('Featured') }}</x-ui.badge>
                        @endif
                    </td>
                    <td class="text-muted">{{ $service->leads()->count() }}</td>
                    <td class="text-right">
                        <a href="{{ route('services.show', $service) }}" class="text-meta" target="_blank" rel="noopener">{{ __('View') }}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </x-ui.table>

    <div class="mt-6">{{ $services->links() }}</div>
</x-layouts::admin>
