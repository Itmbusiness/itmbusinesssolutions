<?php

namespace App\Http\Controllers\Consultant;

use App\Http\Controllers\Controller;
use App\Models\ConsultantCustomer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerNoteController extends Controller
{
    public function store(Request $request, ConsultantCustomer $customer): RedirectResponse
    {
        abort_unless($customer->consultant_id === Auth::id(), 403);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $customer->notes()->create($validated);

        return redirect()->route('consultant.customers.show', $customer)->with('status', 'Note added.');
    }
}
