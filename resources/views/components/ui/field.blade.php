@props([
    'label' => null,
    'for' => null,
    'hint' => null,
    'error' => null,
    'legend' => null,
])

{{--
    Design system §05. Wraps one control with its label, helper text and error.
    Pass `legend` instead of `label` for a group of radios so the grouping is
    announced — that renders a fieldset rather than a bare label.
--}}
@if ($legend)
    <fieldset {{ $attributes->class('field') }}>
        <legend class="field-label">{{ $legend }}</legend>

        {{ $slot }}

        @if ($error)
            <p class="field-error">
                <x-ui.icon name="warning-circle" weight="fill" size="15px" />
                {{ $error }}
            </p>
        @elseif ($hint)
            <p class="field-hint">{{ $hint }}</p>
        @endif
    </fieldset>
@else
    <div {{ $attributes->class('field') }}>
        @if ($label)
            <label class="field-label" for="{{ $for }}">{{ $label }}</label>
        @endif

        {{ $slot }}

        @if ($error)
            <p class="field-error" id="{{ $for }}-error">
                <x-ui.icon name="warning-circle" weight="fill" size="15px" />
                {{ $error }}
            </p>
        @elseif ($hint)
            <p class="field-hint" id="{{ $for }}-hint">{{ $hint }}</p>
        @endif
    </div>
@endif
