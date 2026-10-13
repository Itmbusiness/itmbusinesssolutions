@props([
    'blocks' => [],
    'illustrated' => false,
    'subject' => 'registration',
])

{{--
    Renders a page's markdown body as a stack of section cards instead of one
    flat wall of prose — each `##` heading becomes its own card, and a block
    that is entirely a bullet or numbered list renders as an icon checklist
    (the pattern service pages already use) rather than default markdown
    `<ul>` bullets. Works for both the seeded content pack and anything an
    admin later writes into a page's body from the CMS.
--}}
<div class="flex flex-col gap-5" @if (count($blocks) > 1) data-reveal-stagger @endif>
    @foreach ($blocks as $block)
        @php
            $isList = \App\Support\MarkdownDocument::isBulletList($block['body']);
        @endphp

        <section
            class="{{ $illustrated ? 'public-content-band' : 'card' }}"
            data-reveal="up"
            @if ($block['heading']) aria-labelledby="section-heading-{{ $loop->index }}" @endif
        >
            @if ($illustrated)
                <x-public.media :subject="$subject === 'about' && $loop->first ? 'about' : ($subject === 'about' ? 'registration' : 'requirements')" class="public-content-image" />
            @endif
            <div class="{{ $illustrated ? 'public-content-copy' : '' }}">
            @if ($block['heading'])
                <h2 id="section-heading-{{ $loop->index }}" class="card-title flex items-center gap-2.5">
                    <span aria-hidden="true" class="inline-block size-1.5 flex-none rounded-full bg-teal"></span>
                    {{ $block['heading'] }}
                </h2>
            @endif

            @if ($isList)
                <ul class="{{ $block['heading'] ? 'mt-4' : '' }} flex flex-col gap-3">
                    @foreach (\App\Support\MarkdownDocument::bulletLines($block['body']) as $item)
                        <li class="flex gap-3 text-ink">
                            <x-ui.icon name="check-circle" weight="fill" size="18px" class="mt-0.5 flex-none text-teal" />
                            <span>{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="prose-itm {{ $block['heading'] ? 'mt-3' : '' }}">
                    {!! Str::markdown($block['body']) !!}
                </div>
            @endif
            </div>
        </section>
    @endforeach
</div>
