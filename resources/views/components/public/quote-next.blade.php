<aside class="public-quote-next" aria-labelledby="quote-next-heading">
    <div class="p-6">
        <span class="inline-flex size-12 items-center justify-center rounded-full bg-teal-100 text-teal"><x-ui.icon name="file-text" size="24px" /></span>
        <h2 id="quote-next-heading" class="mt-5 text-h3">{{ __('What happens next') }}</h2>
        <ol class="mt-7 flex flex-col gap-7">
            @foreach ([['user', __('A consultant reviews your request')], ['file-text', __('You receive a written quotation')], ['check-circle', __('You approve before payment')]] as [$icon, $label])
                <li class="flex items-start gap-3"><x-ui.icon :name="$icon" size="24px" class="flex-none text-teal" /><span class="font-semibold">{{ $label }}</span></li>
            @endforeach
        </ol>
    </div>
    <x-public.media subject="quotation" class="public-quote-next-image" />
</aside>
