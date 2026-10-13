@props([
    'label',
    'url',
])

{{--
    A page's own in-body call to action (the content pack's "**CTA:** Label"
    line), rendered as a real component instead of bold text — a teal-tinted
    banner that sits between the page content and the closing quotation CTA.
--}}
<div
    {{ $attributes->class('alert alert-info flex-wrap items-center justify-between gap-4') }}
    role="status"
    data-reveal="up"
>
    <div class="flex items-center gap-3">
        <x-ui.icon name="arrow-circle-right" weight="fill" size="22px" class="flex-none text-teal-700" />
        <p class="alert-title mb-0">{{ $label }}</p>
    </div>

    <x-ui.button :href="$url" variant="secondary" icon-trailing="arrow-right">
        {{ $label }}
    </x-ui.button>
</div>
