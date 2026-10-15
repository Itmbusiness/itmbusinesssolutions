@component('mail::message')
# Confirm your email updates

You asked to receive occasional guidance and service news from ITM Business Solutions. Please confirm that this email address is yours before we send any marketing updates.

@component('mail::button', ['url' => route('marketing.preferences.show', $subscription->access_token)])
Review and confirm
@endcomponent

If you did not ask for these updates, you can ignore this email. Your quotation request will still be handled separately.

Thanks,<br>
ITM Business Solutions
@endcomponent
