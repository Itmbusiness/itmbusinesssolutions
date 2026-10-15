<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LeadStatus;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Lead;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.admin.dashboard', [
            'openLeads' => Lead::query()->open()->count(),
            'awaitingResponseCount' => Lead::query()->awaitingResponse()->count(),
            'awaitingResponseLeads' => Lead::query()->awaitingResponse()->oldest()->with(['service', 'assignee'])->limit(8)->get(),
            'newLeads' => Lead::where('status', LeadStatus::New)->count(),
            'convertedLeads' => Lead::where('status', LeadStatus::Converted)->count(),
            'totalLeads' => Lead::count(),
            'articleCount' => Article::count(),
            'staleArticles' => Article::whereNotNull('next_review_date')
                ->whereDate('next_review_date', '<', now())
                ->count(),
            'recentLeads' => Lead::latest()->with('service')->limit(8)->get(),
        ]);
    }
}
