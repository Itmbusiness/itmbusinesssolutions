@inject('chrome', 'App\Support\SiteChrome')

@if ($chrome->has('site.safety_note'))
    <p {{ $attributes->class('text-[11px] leading-relaxed') }}>
        {{ $chrome->setting('site.safety_note') }}
    </p>
@endif
