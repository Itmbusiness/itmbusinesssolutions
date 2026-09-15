<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LeadStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        return view('pages.admin.leads.index', [
            'leads' => Lead::query()
                ->when($status !== '', fn ($query) => $query->where('status', $status))
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
        ]);
    }

    public function show(Lead $lead): View
    {
        return view('pages.admin.leads.show', [
            'lead' => $lead->load(['service', 'user', 'assignee']),
            'statuses' => LeadStatus::cases(),
            'assignees' => User::whereIn('role', [UserRole::Consultant, UserRole::Admin])->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Lead $lead): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::enum(LeadStatus::class)],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'internal_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $lead->update($validated);

        return back()->with('status', 'Lead updated.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('status', 'Lead deleted.');
    }
}
