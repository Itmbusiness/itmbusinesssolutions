<?php

namespace App\Http\Controllers\Consultant;

use App\Enums\ConsultantCustomerStage;
use App\Http\Controllers\Controller;
use App\Models\ConsultantCustomer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * A consultant's own customer book. Scoped to the signed-in consultant only —
 * never ITM's own leads or clients, and never another consultant's customers.
 */
class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $activeStage = $request->string('stage')->toString();

        $customers = Auth::user()->consultantCustomers()
            ->when($request->filled('q'), fn ($query) => $query->where(fn ($query) => $query
                ->where('name', 'like', '%'.$request->string('q').'%')
                ->orWhere('email', 'like', '%'.$request->string('q').'%')))
            ->when($activeStage !== '', fn ($query) => $query->where('stage', $activeStage))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('pages.consultant.customers.index', [
            'customers' => $customers,
            'stages' => ConsultantCustomerStage::cases(),
            'activeStage' => $activeStage,
        ]);
    }

    public function create(): View
    {
        return view('pages.consultant.customers.form', [
            'customer' => new ConsultantCustomer,
            'stages' => ConsultantCustomerStage::cases(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $customer = Auth::user()->consultantCustomers()->create($this->validated($request));

        return redirect()->route('consultant.customers.show', $customer)->with('status', 'Customer added.');
    }

    public function show(ConsultantCustomer $customer): View
    {
        $this->authorizeOwner($customer);

        return view('pages.consultant.customers.show', [
            'customer' => $customer->load(['notes', 'tasks']),
        ]);
    }

    public function edit(ConsultantCustomer $customer): View
    {
        $this->authorizeOwner($customer);

        return view('pages.consultant.customers.form', [
            'customer' => $customer,
            'stages' => ConsultantCustomerStage::cases(),
        ]);
    }

    public function update(Request $request, ConsultantCustomer $customer): RedirectResponse
    {
        $this->authorizeOwner($customer);

        $customer->update($this->validated($request));

        return redirect()->route('consultant.customers.show', $customer)->with('status', 'Customer saved.');
    }

    public function destroy(ConsultantCustomer $customer): RedirectResponse
    {
        $this->authorizeOwner($customer);

        $customer->delete();

        return redirect()->route('consultant.customers.index')->with('status', 'Customer deleted.');
    }

    private function authorizeOwner(ConsultantCustomer $customer): void
    {
        abort_unless($customer->consultant_id === Auth::id(), 403);
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'stage' => ['required', Rule::enum(ConsultantCustomerStage::class)],
        ]);
    }
}
