@props([
    'variant' => 'primary',
    'icon' => null,
    'iconTrailing' => null,
    'href' => null,
])

{{--
    Design system §04. Primary is filled teal and there is one per view;
    everything else is outlined or ghost. Minimum target is 48px.
--}}
@php
    $variants = [
        'primary' => 'btn-primary',
        'secondary' => 'btn-secondary',
        'quiet' => 'btn-quiet',
        'ghost' => 'btn-ghost',
        'whatsapp' => 'btn-whatsapp',
        'icon' => 'btn-icon',
    ];

    $classes = 'btn '.($variants[$variant] ?? $variants['primary']);
@endphp

<{{ $href ? 'a' : 'button' }}
    @if ($href)
        href="{{ $href }}"
    @else
        type="{{ $attributes->get('type', 'button') }}"
    @endif
    {{ $attributes->except('type')->class($classes) }}
>
    @if ($icon)
        <x-ui.icon :name="$icon" />
    @endif

    {{ $slot }}

    @if ($iconTrailing)
        <x-ui.icon :name="$iconTrailing" />
    @endif
</{{ $href ? 'a' : 'button' }}>
