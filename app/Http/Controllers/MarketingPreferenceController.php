<?php

namespace App\Http\Controllers;

use App\Support\MarketingConsent;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MarketingPreferenceController extends Controller
{
    public function show(string $token, MarketingConsent $consent): View
    {
        return view('pages.public.marketing-preferences', [
            'subscription' => $consent->findByToken($token),
            'token' => $token,
        ]);
    }

    public function confirm(string $token, MarketingConsent $consent): RedirectResponse
    {
        $consent->confirm($token);

        return redirect()->route('marketing.preferences.show', $token)
            ->with('status', 'Your email updates are confirmed.');
    }

    public function unsubscribe(string $token, MarketingConsent $consent): RedirectResponse
    {
        $consent->unsubscribe($token);

        return redirect()->route('marketing.preferences.show', $token)
            ->with('status', 'You will no longer receive marketing emails.');
    }
}
