<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.public.home', [
            'categories' => ServiceCategory::published()->ordered()->withCount('services')->get(),
            'featured' => Service::published()->where('is_featured', true)->ordered()->with('category')->get(),
            'articles' => Article::published()->orderBy('position')->limit(3)->get(),
            'serviceCount' => Service::published()->count(),
            'categoryCount' => ServiceCategory::published()->count(),
        ]);
    }
}
