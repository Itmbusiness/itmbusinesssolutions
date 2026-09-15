<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LeadStatus;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Lead;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.admin.dashboard', [
            'openLeads' => Lead::query()->open()->count(),
            'newLeads' => Lead::where('status', LeadStatus::New)->count(),
            'convertedLeads' => Lead::where('status', LeadStatus::Converted)->count(),
            'totalLeads' => Lead::count(),
            'serviceCount' => Service::count(),
            'unpublishedServices' => Service::where('is_published', false)->count(),
            'categoryCount' => ServiceCategory::count(),
            'articleCount' => Article::count(),
            'staleArticles' => Article::whereNotNull('next_review_date')
                ->whereDate('next_review_date', '<', now())
                ->count(),
            'userCount' => User::count(),
            'recentLeads' => Lead::latest()->with('service')->limit(8)->get(),
        ]);
    }
}
