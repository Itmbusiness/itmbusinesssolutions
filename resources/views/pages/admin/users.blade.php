<x-layouts::admin :title="__('Users')">
    <x-admin.header :title="__('Users')">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-end gap-2">
            <x-ui.field :label="__('Search')" for="q" class="w-full sm:w-56">
                <x-ui.input id="q" name="q" :value="request('q')" />
            </x-ui.field>
            <x-ui.button variant="secondary" type="submit">{{ __('Search') }}</x-ui.button>
        </form>
    </x-admin.header>

    <p class="card-body mt-2 max-w-[60ch]">
        {{ __('Clients and consultants choose their own type at sign-up. Administrator access is granted here and cannot be self-selected.') }}
    </p>

    <x-ui.table class="mt-6" :caption="__('Users')">
        <thead>
            <tr>
                <th scope="col">{{ __('Name') }}</th>
                <th scope="col">{{ __('Requests') }}</th>
                <th scope="col">{{ __('Joined') }}</th>
                <th scope="col">{{ __('Role') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <th scope="row" class="font-normal">
                        {{ $user->name }}
                        <span class="block card-meta">{{ $user->email }}</span>
                    </th>
                    <td class="text-muted">{{ $user->leads_count }}</td>
                    <td class="text-muted">{{ $user->created_at->format('j M Y') }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <x-ui.select name="role" class="w-auto" aria-label="{{ __('Role for :name', ['name' => $user->name]) }}">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->value }}" @selected($user->role === $role)>{{ $role->label() }}</option>
                                @endforeach
                            </x-ui.select>
                            <x-ui.button type="submit" variant="secondary">{{ __('Update') }}</x-ui.button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </x-ui.table>

    <div class="mt-6">{{ $users->links() }}</div>
</x-layouts::admin>
