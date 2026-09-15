@props([
    'steps' => [],
    'current' => 1,
])

{{--
    Design system §06. Desktop shows the whole path; below 640px it collapses
    to "Step N of M" plus the current label, which is what the board specifies.
--}}
@php
    $total = count($steps);
    $currentLabel = $steps[$current - 1] ?? null;
@endphp

<nav aria-label="Progress" {{ $attributes }}>
    <ol class="hidden flex-wrap items-center gap-x-6 gap-y-3 sm:flex">
        @foreach ($steps as $index => $label)
            @php
                $number = $index + 1;
                $state = $number < $current ? 'done' : ($number === $current ? 'current' : 'upcoming');
            @endphp

            <li
                @class(['step', 'step-done' => $state === 'done', 'step-current' => $state === 'current'])
                @if ($state === 'current') aria-current="step" @endif
            >
                <span class="step-marker">
                    @if ($state === 'done')
                        <x-ui.icon name="check" size="14px" />
                        <span class="sr-only">Completed:</span>
                    @else
                        {{ $number }}
                    @endif
                </span>
                {{ $label }}
            </li>
        @endforeach
    </ol>

    <p class="sm:hidden">
        <span class="card-meta">Step {{ $current }} of {{ $total }}</span>
        <span class="block font-semibold">{{ $currentLabel }}</span>
    </p>
</nav>
