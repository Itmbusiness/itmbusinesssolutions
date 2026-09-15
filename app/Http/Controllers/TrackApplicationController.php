<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Guest tracking (FR-09). A reference alone is not enough — the email on the
 * record must match too, and only limited, client-safe fields are returned.
 * Internal notes and assignee never appear here.
 */
class TrackApplicationController extends Controller
{
    public function show(): View
    {
        return view('pages.public.track', ['lead' => null, 'searched' => false]);
    }

    public function lookup(Request $request): View
    {
        $validated = $request->validate([
            'reference' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $lead = Lead::query()
            ->where('reference', strtoupper(trim($validated['reference'])))
            ->whereRaw('lower(email) = ?', [strtolower($validated['email'])])
            ->first();

        return view('pages.public.track', [
            'lead' => $lead,
            'searched' => true,
        ]);
    }
}
