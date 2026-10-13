<x-layouts::public :title="$page->seo_title ?? $page->title" :meta-description="$page->meta_description" :indexable="$page->is_indexable">
    <div class="public-contact-page">
        <x-public.page-header :heading="$page->title" :lead="$lead" image="quotation" />

        <div class="page-shell public-contact-section">
            <div class="public-contact-layout">
                <section aria-labelledby="contact-form-heading" class="public-contact-form">
                    <x-public.contact-form :services="$contactServices" />
                </section>
                <x-public.contact-channels />
            </div>

            @if ($blocks !== [])
                <div class="mt-10"><x-public.rich-content :blocks="$blocks" /></div>
            @endif

            @if ($page->cta_label && $page->cta_url)
                <x-public.inline-cta :label="$page->cta_label" :url="$page->cta_url" class="mt-8" />
            @endif
        </div>
    </div>
</x-layouts::public>
