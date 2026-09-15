@props([
    'type' => 'text',
    'invalid' => false,
])

{{-- Design system §05 — 48px minimum height, teal focus ring, never the browser default. --}}
<input
    type="{{ $type }}"
    @if ($invalid) aria-invalid="true" @endif
    {{ $attributes->class('input') }}
>
