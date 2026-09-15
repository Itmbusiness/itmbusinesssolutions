@props([
    'icon' => 'tray',
    'title',
])

{{-- Design system §09 — empty and permission states share this shape. --}}
<div {{ $attributes->class('card text-center') }}>
    <span class="mx-auto mb-4 flex size-14 items-center justify-center rounded-full bg-page text-navy-300">
        <x-ui.icon :name="$icon" size="26px" />
    </span>

    <p class="text-lg font-semibold">{{ $title }}</p>

    @if (trim($slot) !== '')
        <div class="card-body mx-auto mt-2 max-w-[34ch]">{{ $slot }}</div>
    @endif

    @isset($action)
        <div class="mt-5">{{ $action }}</div>
    @endisset
</div>
