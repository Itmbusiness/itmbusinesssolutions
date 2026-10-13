<?php

namespace App\Http\Controllers\Consultant;

use App\Http\Controllers\Controller;
use App\Models\ConsultantCustomer;
use App\Models\ConsultantTask;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function store(Request $request, ConsultantCustomer $customer): RedirectResponse
    {
        abort_unless($customer->consultant_id === Auth::id(), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'due_on' => ['nullable', 'date'],
        ]);

        $customer->tasks()->create([
            ...$validated,
            'consultant_id' => Auth::id(),
        ]);

        return redirect()->route('consultant.customers.show', $customer)->with('status', 'Task added.');
    }

    public function toggle(ConsultantTask $task): RedirectResponse
    {
        abort_unless($task->consultant_id === Auth::id(), 403);

        $task->update(['completed_at' => $task->completed_at ? null : now()]);

        return back()->with('status', $task->completed_at ? 'Task marked done.' : 'Task reopened.');
    }

    public function destroy(ConsultantTask $task): RedirectResponse
    {
        abort_unless($task->consultant_id === Auth::id(), 403);

        $task->delete();

        return back()->with('status', 'Task removed.');
    }
}
