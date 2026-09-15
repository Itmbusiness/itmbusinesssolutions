@props([
    'title',
    'back' => null,
    'backLabel' => null,
])

<header class="flex flex-wrap items-end justify-between gap-4">
    <div>
        @if ($back)
            <a href="{{ $back }}" class="text-meta text-teal">&larr; {{ $backLabel ?? __('Back') }}</a>
        @else
            <p class="text-eyebrow uppercase text-teal">{{ __('Admin') }}</p>
        @endif
        <h1 class="mt-2 text-h2">{{ $title }}</h1>
    </div>

    <div class="flex flex-wrap items-end gap-2">{{ $slot }}</div>
</header>
