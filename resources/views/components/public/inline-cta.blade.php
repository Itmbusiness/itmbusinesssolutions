@props([
    'label',
    'url',
])

{{--
    A page's own in-body call to action (the content pack's "**CTA:** Label"
    line), rendered as a real component instead of bold text — a teal-tinted
    banner that sits between the page content and the closing quotation CTA.
--}}
<div {{ $attributes->class('alert alert-info') }} data-reveal="up">
    <a href="{{ $url }}" class="inline-flex min-h-12 items-center gap-3 font-semibold text-teal-700">
        <x-ui.icon name="arrow-circle-right" weight="fill" size="22px" class="flex-none" />
        <span>{{ $label }}</span>
        <x-ui.icon name="arrow-right" size="18px" class="flex-none" />
    </a>
</div>
