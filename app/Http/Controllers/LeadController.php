<?php

namespace App\Http\Controllers;

use App\Enums\ServiceGoal;
use App\Mail\ConfirmMarketingSubscription;
use App\Mail\LeadConfirmation;
use App\Mail\NewLeadReceived;
use App\Models\Lead;
use App\Models\Service;
use App\Models\Setting;
use App\Support\MarketingConsent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * The signed-in version of a service request — Journey B in the PRD. Known
 * account details pre-fill the record, so the client only supplies what is new.
 */
class LeadController extends Controller
{
    public function index(Request $request): View
    {
        return view('pages.service-requests.index', [
            'leads' => $request->user()->leads()->with('service')->latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('pages.service-requests.create', [
            'goals' => ServiceGoal::cases(),
            'services' => Service::published()->ordered()->with('category')->get()->groupBy(fn (Service $s) => $s->category->name),
        ]);
    }

    public function store(Request $request, MarketingConsent $marketingConsent): RedirectResponse
    {
        $validated = $request->validate([
            'goal' => ['required', Rule::enum(ServiceGoal::class)],
            'service_id' => ['nullable', 'exists:services,id'],
            'message' => ['nullable', 'string', 'max:2000'],
            'preferred_channel' => ['required', 'in:whatsapp,email,phone'],
            'consent' => ['accepted'],
            'marketing_opt_in' => ['sometimes', 'accepted'],
        ]);

        $user = Auth::user();

        [$lead, $subscription] = DB::transaction(function () use ($request, $validated, $user, $marketingConsent): array {
            $lead = Lead::create([
                'user_id' => $user->id,
                'service_id' => $validated['service_id'] ?? null,
                'name' => $user->name,
                'email' => $user->email,
                'goal' => $validated['goal'],
                'message' => $validated['message'] ?? null,
                'preferred_channel' => $validated['preferred_channel'],
                'source' => 'workspace',
                'consent_at' => now(),
                'utm_source' => $request->session()->get('lead_attribution.utm_source') ?: null,
                'utm_medium' => $request->session()->get('lead_attribution.utm_medium') ?: null,
                'utm_campaign' => $request->session()->get('lead_attribution.utm_campaign') ?: null,
            ]);

            $subscription = $request->boolean('marketing_opt_in')
                ? $marketingConsent->requestForLead($lead, 'workspace_request')
                : null;

            return [$lead, $subscription];
        });

        if ($contactEmail = Setting::get('contact.email')) {
            Mail::to($contactEmail)->send(new NewLeadReceived($lead));
        }
        Mail::to($lead->email)->send(new LeadConfirmation($lead));
        if ($subscription) {
            Mail::to($subscription->email)->send(new ConfirmMarketingSubscription($subscription));
        }

        return redirect()
            ->route('dashboard')
            ->with('status', "Thanks — your request {$lead->reference} is with a consultant, who will follow up.")
            ->with('marketing_confirmation_pending', $subscription !== null);
    }
}
