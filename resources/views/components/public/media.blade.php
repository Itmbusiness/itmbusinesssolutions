@props(['subject' => 'registration', 'alt' => '', 'priority' => false])
@php
    $path = \App\Support\PublicMedia::path($subject);
    $smallPath = $path ? Str::replaceLast('.webp', '-640.webp', $path) : null;
    $dimensions = $path && is_file(public_path($path)) ? getimagesize(public_path($path)) : false;
@endphp

@if ($path)
<img
    src="{{ asset($path) }}"
    @if (str_ends_with($path, '.webp') && is_file(public_path($smallPath)))
        srcset="{{ asset($smallPath) }} 640w, {{ asset($path) }} 1536w"
        sizes="{{ $priority ? '100vw' : '(max-width: 767px) 100vw, 50vw' }}"
    @endif
    alt="{{ $alt }}"
    width="{{ $dimensions[0] ?? 1536 }}" height="{{ $dimensions[1] ?? 1024 }}"
    loading="{{ $priority ? 'eager' : 'lazy' }}"
    @if ($priority) fetchpriority="high" @endif
    decoding="async"
    {{ $attributes->class(['public-media']) }}
>
@endif
