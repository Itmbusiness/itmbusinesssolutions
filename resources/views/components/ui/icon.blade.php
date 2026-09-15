@props([
    'name',
    'weight' => 'regular',
    'size' => null,
])

{{--
    Phosphor icon (design system §"Icons"). Decorative by default — pass an
    aria-label to promote it to a labelled graphic when it carries meaning
    the surrounding text does not.
--}}
<i
    @class([
        $weight === 'fill' ? 'ph-fill' : 'ph',
        'ph-'.$name,
    ])
    @if ($size) style="font-size: {{ $size }}" @endif
    {{ $attributes->merge(['aria-hidden' => $attributes->has('aria-label') ? 'false' : 'true']) }}
></i>
