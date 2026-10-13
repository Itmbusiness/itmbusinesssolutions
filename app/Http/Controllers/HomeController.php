<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Page;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.public.home', [
            'page' => Page::published()->where('slug', 'home')->first(),
            'categories' => ServiceCategory::published()->ordered()->withCount(['services' => fn ($query) => $query->published()])->get(),
            'featured' => Service::published()->where('is_featured', true)->ordered()->with('category')->get(),
            'articles' => Article::published()->with('category')->orderBy('position')->limit(3)->get(),
            'serviceCount' => Service::published()->count(),
            'categoryCount' => ServiceCategory::published()->count(),
        ]);
    }
}
