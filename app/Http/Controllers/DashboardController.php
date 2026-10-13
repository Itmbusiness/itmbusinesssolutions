<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Branches on role: a client sees their own requests to ITM, a consultant
 * sees their own, entirely separate customer book. Never both.
 */
class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        if ($user->isConsultant()) {
            return view('dashboard', [
                'customers' => $user->consultantCustomers()->latest()->take(5)->get(),
                'customerCount' => $user->consultantCustomers()->count(),
                'tasks' => $user->consultantTasks()->open()->with('customer')->orderBy('due_on')->take(5)->get(),
                'openTaskCount' => $user->consultantTasks()->open()->count(),
            ]);
        }

        return view('dashboard', [
            'leads' => $user->leads()->latest()->take(5)->get(),
            'openLeadCount' => $user->leads()->open()->count(),
        ]);
    }
}
