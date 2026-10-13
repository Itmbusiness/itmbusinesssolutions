@component('mail::message')
# New lead: {{ $lead->reference }}

**Name:** {{ $lead->name }}
**Email:** {{ $lead->email }}
**Phone:** {{ $lead->phone ?? 'Not provided' }}
**Organisation:** {{ $lead->organisation_name ?? 'Not provided' }}
**Service:** {{ $lead->service?->name ?? 'Not specified' }}
**Preferred contact:** {{ ucfirst($lead->preferred_channel) }}

@if ($lead->message)
**Message:**

{{ $lead->message }}
@endif

@component('mail::button', ['url' => route('admin.leads.show', $lead)])
View lead
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
