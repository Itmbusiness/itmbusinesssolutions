@props([
    'variant' => 'info',
    'title' => null,
    'icon' => null,
])

{{-- Design system §09. --}}
@php
    $variants = [
        'info' => ['class' => 'alert-info', 'icon' => 'info'],
        'attention' => ['class' => 'alert-attention', 'icon' => 'warning'],
        'problem' => ['class' => 'alert-problem', 'icon' => 'x-circle'],
        'success' => ['class' => 'alert-success', 'icon' => 'check-circle'],
    ];

    $resolved = $variants[$variant] ?? $variants['info'];
@endphp

<div
    role="{{ in_array($variant, ['problem', 'attention'], true) ? 'alert' : 'status' }}"
    {{ $attributes->class('alert '.$resolved['class']) }}
>
    <x-ui.icon :name="$icon ?? $resolved['icon']" weight="fill" />

    <div>
        @if ($title)
            <div class="alert-title">{{ $title }}</div>
        @endif

        <div class="alert-body">{{ $slot }}</div>
    </div>
</div>
