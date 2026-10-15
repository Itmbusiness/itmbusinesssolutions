<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LeadStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\MarketingSubscription;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();
        $awaitingResponse = $request->query('response') === 'waiting';

        return view('pages.admin.leads.index', [
            'leads' => Lead::query()
                ->when($status !== '', fn ($query) => $query->where('status', $status))
                ->when($awaitingResponse, fn ($query) => $query->awaitingResponse())
                ->when($request->filled('q'), function ($query) use ($request) {
                    $term = '%'.$request->string('q').'%';
                    $query->where(fn ($q) => $q
                        ->where('name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('reference', 'like', $term)
                        ->orWhere('organisation_name', 'like', $term));
                })
                ->with(['service', 'assignee'])
                ->latest()
                ->paginate(20)
                ->withQueryString(),
            'statuses' => LeadStatus::cases(),
            'activeStatus' => $status,
            'awaitingResponse' => $awaitingResponse,
        ]);
    }

    public function show(Lead $lead): View
    {
        return view('pages.admin.leads.show', [
            'lead' => $lead->load(['service', 'user', 'assignee', 'responseRecorder', 'marketingConsentEvents']),
            'marketingSubscription' => $lead->marketingConsentEvents->contains('action', 'requested')
                ? MarketingSubscription::where('email', mb_strtolower(trim($lead->email)))->first()
                : null,
            'statuses' => LeadStatus::cases(),
            'assignees' => User::where('role', UserRole::Admin)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Lead $lead): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::enum(LeadStatus::class)],
            'assigned_to' => ['nullable', Rule::exists('users', 'id')->where('role', UserRole::Admin->value)],
            'internal_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        if (array_key_exists('assigned_to', $validated) && $validated['assigned_to'] != $lead->assigned_to) {
            $validated['assigned_at'] = $validated['assigned_to'] === null ? null : now();
        }

        $lead->update($validated);

        return back()->with('status', 'Lead updated.');
    }

    public function recordResponse(Lead $lead): RedirectResponse
    {
        Lead::whereKey($lead->id)->whereNull('first_responded_at')->update([
            'first_responded_at' => now(),
            'response_recorded_by' => Auth::id(),
        ]);

        return back()->with('status', 'First human response recorded.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('status', 'Lead deleted.');
    }
}
