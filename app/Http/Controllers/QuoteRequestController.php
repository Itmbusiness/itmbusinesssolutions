<?php

namespace App\Http\Controllers;

use App\Enums\ServiceGoal;
use App\Mail\LeadConfirmation;
use App\Mail\NewLeadReceived;
use App\Models\Lead;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Guest quotation requests — Journey A in the PRD. No account is required, so
 * this is the site's primary conversion path.
 */
class QuoteRequestController extends Controller
{
    public function create(Request $request): View
    {
        $service = $request->filled('service')
            ? Service::published()->where('slug', $request->string('service'))->first()
            : null;

        return view('pages.public.quote', [
            'service' => $service,
            'services' => Service::published()->ordered()->with('category')->get()->groupBy(fn (Service $s) => $s->category->name),
            'goals' => ServiceGoal::cases(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'organisation_name' => ['nullable', 'string', 'max:255'],
            'organisation_status' => ['nullable', 'in:not-registered,registered,unsure'],
            'service_id' => ['nullable', 'exists:services,id'],
            'goal' => ['nullable', Rule::enum(ServiceGoal::class)],
            'message' => ['nullable', 'string', 'max:2000'],
            'preferred_channel' => ['required', 'in:whatsapp,email,phone'],
            'urgency' => ['nullable', 'in:exploring,this-month,urgent'],
            'consent' => ['accepted'],
            // Honeypot: a real person never fills a hidden field.
            'website' => ['prohibited'],
        ], [
            'consent.accepted' => 'Please confirm that ITM Business Solutions may contact you about this request.',
        ]);

        $lead = Lead::create([
            'user_id' => Auth::id(),
            'service_id' => $validated['service_id'] ?? null,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'organisation_name' => $validated['organisation_name'] ?? null,
            'organisation_status' => $validated['organisation_status'] ?? null,
            'goal' => $validated['goal'] ?? null,
            'message' => $validated['message'] ?? null,
            'preferred_channel' => $validated['preferred_channel'],
            'urgency' => $validated['urgency'] ?? null,
            'consent_at' => now(),
            'utm_source' => $request->session()->get('lead_attribution.utm_source') ?: null,
            'utm_medium' => $request->session()->get('lead_attribution.utm_medium') ?: null,
            'utm_campaign' => $request->session()->get('lead_attribution.utm_campaign') ?: null,
        ]);

        if ($contactEmail = Setting::get('contact.email')) {
            Mail::to($contactEmail)->send(new NewLeadReceived($lead));
        }
        Mail::to($lead->email)->send(new LeadConfirmation($lead));

        return redirect()
            ->route('quote.received')
            ->with('lead_reference', $lead->reference);
    }

    public function received(Request $request): View
    {
        return view('pages.public.quote-received', [
            'reference' => $request->session()->get('lead_reference'),
        ]);
    }
}
