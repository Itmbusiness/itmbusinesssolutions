@props([
    'kicker' => null,
    'title' => null,
    'elevation' => 'sm',
    'navy' => false,
])

{{-- Design system §08. Cards are #fff on the off-white page ground, 18px radius. --}}
<div
    {{ $attributes->class([
        'card',
        'card-navy' => $navy,
        'elev-'.$elevation => in_array($elevation, ['sm', 'md', 'lg'], true),
    ]) }}
>
    @if ($kicker)
        <div class="card-kicker">{{ $kicker }}</div>
    @endif

    @if ($title)
        <h3 class="card-title {{ $kicker ? 'mt-2' : '' }}">{{ $title }}</h3>
    @endif

    {{ $slot }}
</div>
