@props([
    'invalid' => false,
])

{{-- Design system §05 — native select with the system's own chevron. --}}
<select
    @if ($invalid) aria-invalid="true" @endif
    {{ $attributes->class('input') }}
>{{ $slot }}</select>
