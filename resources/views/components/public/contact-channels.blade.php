@inject('chrome', 'App\Support\SiteChrome')

<aside class="public-contact-channels" aria-labelledby="contact-channels-heading">
    <h2 id="contact-channels-heading" class="text-h3">{{ __('Contact ITM Business Solutions') }}</h2>
    <dl class="public-contact-details mt-5">
        @if ($chrome->has('contact.whatsapp'))
            <div>
                <dt><x-ui.icon name="whatsapp-logo" size="24px" class="text-success" />{{ __('WhatsApp') }}</dt>
                <dd><a href="{{ 'https://wa.me/'.preg_replace('/\D/', '', $chrome->setting('contact.whatsapp')) }}">{{ $chrome->setting('contact.whatsapp') }}<x-ui.icon name="arrow-up-right" size="16px" /></a></dd>
            </div>
        @endif
        @if ($chrome->has('contact.phone'))
            <div>
                <dt><x-ui.icon name="phone" size="24px" class="text-teal" />{{ __('Telephone') }}</dt>
                <dd><a href="{{ 'tel:'.preg_replace('/[^\d+]/', '', $chrome->setting('contact.phone')) }}">{{ $chrome->setting('contact.phone') }}</a></dd>
            </div>
        @endif
        @if ($chrome->has('contact.email'))
            <div>
                <dt><x-ui.icon name="envelope-simple" size="24px" class="text-teal" />{{ __('Email') }}</dt>
                <dd><a href="mailto:{{ $chrome->setting('contact.email') }}">{{ $chrome->setting('contact.email') }}</a></dd>
            </div>
        @endif
        @if ($chrome->has('contact.address'))
            <div>
                <dt><x-ui.icon name="map-pin" size="24px" class="text-teal" />{{ __('Office') }}</dt>
                <dd class="whitespace-pre-line">{{ $chrome->setting('contact.address') }}</dd>
                <dd class="mt-2"><a href="{{ 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($chrome->setting('contact.address')) }}">{{ __('Get directions') }}<x-ui.icon name="arrow-up-right" size="16px" /></a></dd>
            </div>
        @endif
        @if ($chrome->has('contact.hours'))
            <div>
                <dt><x-ui.icon name="clock" size="24px" class="text-teal" />{{ __('Office hours') }}</dt>
                <dd>{{ $chrome->setting('contact.hours') }}</dd>
            </div>
        @endif
    </dl>
    <div class="public-contact-quote mt-7 border-t border-line pt-6">
        <x-ui.button variant="secondary" href="{{ route('quote.create') }}" icon-trailing="arrow-right">{{ __('Request a Quotation') }}</x-ui.button>
    </div>
</aside>
