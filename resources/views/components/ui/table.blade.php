@props([
    'caption' => null,
])

{{--
    Design system §07. The scroll container carries the card chrome so wide
    tables scroll inside their own box rather than pushing the page sideways.
--}}
<div {{ $attributes->class('overflow-x-auto rounded-card border border-line bg-white elev-sm') }}>
    <table class="table">
        @if ($caption)
            <caption class="sr-only">{{ $caption }}</caption>
        @endif

        {{ $slot }}
    </table>
</div>
