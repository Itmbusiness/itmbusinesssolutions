@props([
    'invalid' => false,
])

{{-- Design system §05. --}}
<textarea
    @if ($invalid) aria-invalid="true" @endif
    {{ $attributes->class('input') }}
>{{ $slot }}</textarea>
