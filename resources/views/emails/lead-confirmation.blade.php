@component('mail::message')
# Thanks, {{ $lead->name }}

We've received your request and a consultant will follow up by
{{ $lead->preferred_channel }} shortly.

**Your reference:** {{ $lead->reference }}

Keep this reference handy — you can use it to track your request at any time.

@component('mail::button', ['url' => route('track')])
Track your request
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
