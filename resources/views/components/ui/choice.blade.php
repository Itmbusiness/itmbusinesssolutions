@props([
    'type' => 'radio',
    'name' => null,
    'value' => null,
    'checked' => false,
])

{{--
    Design system §05 — the whole tile is the label, so the hit target clears
    48px and the checked state reads from the tint, not from the dot alone.
--}}
<label class="choice">
    <input
        type="{{ $type }}"
        @if ($name) name="{{ $name }}" @endif
        @if (! is_null($value)) value="{{ $value }}" @endif
        @checked($checked)
        {{ $attributes }}
    >
    <span>{{ $slot }}</span>
</label>
