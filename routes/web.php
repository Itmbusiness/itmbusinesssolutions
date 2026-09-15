<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\QuoteRequestController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TrackApplicationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public site
|--------------------------------------------------------------------------
| Every route below renders from the database so the owner can change copy,
| services and articles from the admin without a deploy.
*/

Route::get('/', HomeController::class)->name('home');

Route::get('services', [ServiceController::class, 'index'])->name('services.index');
Route::get('services/category/{category}', [ServiceController::class, 'category'])->name('services.category');
Route::get('services/{service}', [ServiceController::class, 'show'])->name('services.show');

Route::get('packages', [PageController::class, 'packages'])->name('packages');

Route::get('articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('articles/{article}', [ArticleController::class, 'show'])->name('articles.show');

Route::get('request-a-quotation', [QuoteRequestController::class, 'create'])->name('quote.create');
Route::post('request-a-quotation', [QuoteRequestController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('quote.store');
Route::get('request-received', [QuoteRequestController::class, 'received'])->name('quote.received');

Route::get('track', [TrackApplicationController::class, 'show'])->name('track');
Route::post('track', [TrackApplicationController::class, 'lookup'])
    ->middleware('throttle:10,1')
    ->name('track.lookup');

Route::get('sitemap.xml', SitemapController::class)->name('sitemap');

// Living reference for the ITM design system. Not linked from any navigation —
// reachable only by visiting the URL directly, and only outside production.
if (! app()->isProduction()) {
    Route::view('design-system', 'pages.design-system')->name('design-system');
}

/*
|--------------------------------------------------------------------------
| Authenticated workspace
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::get('service-requests/create', [LeadController::class, 'create'])
        ->name('service-requests.create');
    Route::post('service-requests', [LeadController::class, 'store'])
        ->name('service-requests.store');
});

require __DIR__.'/admin.php';
require __DIR__.'/settings.php';

/*
|--------------------------------------------------------------------------
| Editable static pages
|--------------------------------------------------------------------------
| Declared last so a catch-all slug never shadows a real route above.
*/

Route::get('{slug}', [PageController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('page');
