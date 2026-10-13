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
 * The signed-in version of a service request — Journey B in the PRD. Known
 * account details pre-fill the record, so the client only supplies what is new.
 */
class LeadController extends Controller
{
    public function create(): View
    {
        return view('pages.service-requests.create', [
            'goals' => ServiceGoal::cases(),
            'services' => Service::published()->ordered()->with('category')->get()->groupBy(fn (Service $s) => $s->category->name),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'goal' => ['required', Rule::enum(ServiceGoal::class)],
            'service_id' => ['nullable', 'exists:services,id'],
            'message' => ['nullable', 'string', 'max:2000'],
            'preferred_channel' => ['required', 'in:whatsapp,email,phone'],
            'consent' => ['accepted'],
        ]);

        $user = Auth::user();

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

        if ($contactEmail = Setting::get('contact.email')) {
            Mail::to($contactEmail)->send(new NewLeadReceived($lead));
        }
        Mail::to($lead->email)->send(new LeadConfirmation($lead));

        return redirect()
            ->route('dashboard')
            ->with('status', "Thanks — your request {$lead->reference} is with a consultant, who will follow up.");
    }
}
