@props(['label', 'value', 'note', 'href'])

<a href="{{ $href }}" {{ $attributes->class('card card-lift block no-underline hover:no-underline') }}>
    <p class="card-kicker">{{ $label }}</p>
    <p class="stat-figure mt-2 text-h1 font-semibold text-navy tabular-nums">{{ $value }}</p>
    <p class="card-meta mt-1">{{ $note }}</p>
</a>
