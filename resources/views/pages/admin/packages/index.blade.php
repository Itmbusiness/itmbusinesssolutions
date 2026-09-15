<x-layouts::admin :title="__('Packages')">
    <x-admin.header :title="__('Packages')">
        <x-ui.button href="{{ route('admin.packages.create') }}" icon="plus">{{ __('New package') }}</x-ui.button>
    </x-admin.header>

    <x-ui.table class="mt-6" :caption="__('Packages')">
        <thead>
            <tr>
                <th scope="col">{{ __('Name') }}</th>
                <th scope="col">{{ __('Summary') }}</th>
                <th scope="col">{{ __('Status') }}</th>
                <th scope="col">{{ __('Order') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($packages as $package)
                <tr>
                    <th scope="row" class="font-normal">
                        <a href="{{ route('admin.packages.edit', $package) }}">{{ $package->name }}</a>
                    </th>
                    <td class="text-muted">{{ Str::limit($package->summary, 70) }}</td>
                    <td>
                        <x-ui.badge :variant="$package->is_published ? 'success' : 'neutral'">
                            {{ $package->is_published ? __('Published') : __('Draft') }}
                        </x-ui.badge>
                    </td>
                    <td class="text-muted">{{ $package->position }}</td>
                </tr>
            @endforeach
        </tbody>
    </x-ui.table>
</x-layouts::admin>
