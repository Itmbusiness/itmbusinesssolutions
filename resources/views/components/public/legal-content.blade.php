@props(['blocks' => []])

<div class="grid items-start gap-10 lg:grid-cols-[240px_minmax(0,1fr)]">
    <nav aria-label="{{ __('Page contents') }}" class="border-b border-line pb-6 lg:border-b-0 lg:pb-0">
        <h2 class="text-base font-semibold text-navy">{{ __('On this page') }}</h2>
        <ol class="mt-4 flex flex-col">
            @foreach ($blocks as $block)
                @if ($block['heading'])
                    <li><a href="#legal-section-{{ $loop->index }}" class="flex min-h-12 items-center py-2 text-meta text-teal hover:underline focus-visible:outline-2 focus-visible:outline-teal">{{ $block['heading'] }}</a></li>
                @endif
            @endforeach
        </ol>
    </nav>
    <article class="min-w-0 max-w-3xl">
        @foreach ($blocks as $block)
            <section class="scroll-mt-28 border-b border-line py-6 first:pt-0 last:border-b-0" @if ($block['heading']) aria-labelledby="legal-section-{{ $loop->index }}" @endif>
                @if ($block['heading'])
                    <h2 id="legal-section-{{ $loop->index }}" class="text-h3 text-navy">{{ $block['heading'] }}</h2>
                @endif
                <div class="prose-itm {{ $block['heading'] ? 'mt-3' : '' }}">
                    {!! Str::markdown($block['body'], ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
                </div>
            </section>
        @endforeach
    </article>
</div>
