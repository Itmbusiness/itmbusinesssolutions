<x-ui.field :error="$errors->first('marketing_opt_in')">
    <x-ui.choice type="checkbox" name="marketing_opt_in" value="1" :checked="(bool) old('marketing_opt_in')">
        {{ __('I would also like occasional email guidance and service news from ITM Business Solutions. I can unsubscribe at any time.') }}
    </x-ui.choice>
    <p class="field-hint mt-2">
        {{ __('Optional. We will email you to confirm this choice before sending updates.') }}
        <a href="{{ route('page', 'privacy') }}">{{ __('Privacy Policy') }}</a>
    </p>
</x-ui.field>
