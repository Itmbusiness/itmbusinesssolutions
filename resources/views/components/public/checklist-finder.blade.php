@props(['services'])

<section class="mb-12" aria-labelledby="checklist-finder-heading" x-data="{ query: '' }">
    <h2 id="checklist-finder-heading" class="text-h2">{{ __('Find a checklist') }}</h2>
    <div class="mt-5 max-w-160">
        <x-ui.field :label="__('Search by service, organisation type or document name')" for="checklist-search">
            <x-ui.input type="search" id="checklist-search" x-model="query" autocomplete="off" />
        </x-ui.field>
    </div>
    <div class="mt-6 grid gap-4 md:grid-cols-2">
        @foreach ($services as $service)
            @php
                $searchText = Str::lower(implode(' ', [$service->name, $service->category->name, ...($service->requirements ?? []), ...($service->suited_to ?? [])]));
            @endphp
            <details class="card" data-checklist data-search="{{ $searchText }}" x-show="!query.trim() || $el.dataset.search.includes(query.trim().toLowerCase())">
                <summary class="flex min-h-12 cursor-pointer items-center justify-between gap-3 font-semibold">
                    {{ $service->name }}
                    <x-ui.icon name="caret-down" size="18px" />
                </summary>
                <ul class="mt-4 flex flex-col gap-3">
                    @foreach ($service->requirements ?? [] as $requirement)
                        <li class="flex gap-2"><x-ui.icon name="check" class="mt-1 flex-none text-teal" /><span>{{ $requirement }}</span></li>
                    @endforeach
                </ul>
                @if ($service->reviewed_on)
                    <p class="mt-4 text-meta text-muted">{{ __('Last reviewed') }}: {{ $service->reviewed_on->format('j M Y') }}</p>
                @endif
                <a class="mt-4 inline-flex min-h-12 items-center gap-2 font-semibold" href="{{ route('services.show', $service) }}">{{ __('View service') }}<x-ui.icon name="arrow-right" /></a>
            </details>
        @endforeach
    </div>
    <p class="mt-4 text-muted" role="status" x-cloak x-show="query.trim() && !Array.from($el.parentElement.querySelectorAll('[data-checklist]')).some(item => item.dataset.search.includes(query.trim().toLowerCase()))">{{ __('No matching checklists.') }}</p>
</section>
