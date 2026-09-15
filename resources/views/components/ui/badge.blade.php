@props([
    'variant' => 'neutral',
    'icon' => null,
])

{{--
    Design system §04. Status is carried by colour *plus* icon *plus* text —
    never colour alone — so every variant but `featured` ships a default glyph.
--}}
@php
    $variants = [
        'info' => ['class' => 'badge-info', 'icon' => 'clock'],
        'success' => ['class' => 'badge-success', 'icon' => 'check-circle'],
        'attention' => ['class' => 'badge-attention', 'icon' => 'warning'],
        'problem' => ['class' => 'badge-problem', 'icon' => 'x-circle'],
        'neutral' => ['class' => 'badge-neutral', 'icon' => 'minus-circle'],
        'featured' => ['class' => 'badge-featured', 'icon' => null],
    ];

    $resolved = $variants[$variant] ?? $variants['neutral'];
    $glyph = $icon ?? $resolved['icon'];
@endphp

<span {{ $attributes->class('badge '.$resolved['class']) }}>
    @if ($glyph)
        <x-ui.icon :name="$glyph" weight="fill" size="14px" />
    @endif

    {{ $slot }}
</span>
