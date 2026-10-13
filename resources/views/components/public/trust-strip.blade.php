<section class="border-b border-line bg-white" aria-label="{{ __('How we work') }}">
    <div class="page-shell-wide grid gap-6 py-7 sm:grid-cols-3">
        @foreach ([['file-text', __('Clear requirements'), __('Know what you need before you start.')], ['tag', __('Written quotation first'), __('Approve the scope and charges before payment.')], ['chat-circle', __('Clear communication'), __('A consultant reviews your request.')]] as [$icon, $title, $body])
            <div class="flex items-start gap-4">
                <x-ui.icon :name="$icon" size="26px" class="mt-1 flex-none text-teal" />
                <div><h2 class="font-semibold">{{ $title }}</h2><p class="mt-1 text-meta text-muted">{{ $body }}</p></div>
            </div>
        @endforeach
    </div>
</section>
