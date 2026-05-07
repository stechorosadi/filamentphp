<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// XML sitemap — no locale prefix needed (for search engines)
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Root redirect to default locale
Route::get('/', fn () => redirect('/id/'))->name('root');

// All frontend routes under /{locale}/
Route::prefix('{locale}')
    ->where(['locale' => 'en|id'])
    ->middleware('setlocale')
    ->group(function (): void {
        Route::middleware('throttle:60,1')->group(function (): void {
            Route::get('/', [HomeController::class, 'index'])->name('home');
            Route::get('/search', [HomeController::class, 'search'])->name('search');
            Route::get('/sitemap', [SitemapController::class, 'html'])->name('sitemap.html');
            Route::get('/categories/{slug}', [HomeController::class, 'category'])->name('category.show');
            Route::get('/classifications/{slug}', [HomeController::class, 'classification'])->name('classification.show');
            Route::get('/tags/{slug}', [HomeController::class, 'tag'])->name('tag.show');
            Route::get('/archive', [HomeController::class, 'archive'])->name('archive');
            Route::get('/team', [HomeController::class, 'team'])->name('team');
            Route::get('/team/{member}', [HomeController::class, 'memberShow'])->name('team.member');
            Route::get('/team/{member}/pdf', [HomeController::class, 'memberPdf'])->name('team.member.pdf');
            Route::get('/contact', [ContactController::class, 'show'])->name('contact');
        });

        Route::get('/articles/{slug}', [HomeController::class, 'show'])
            ->name('content.show')
            ->middleware('throttle:30,1');

        Route::get('/articles/{slug}/pdf', [HomeController::class, 'pdf'])
            ->name('content.pdf')
            ->middleware('throttle:10,1');

        Route::post('/contact', [ContactController::class, 'store'])
            ->name('contact.store')
            ->middleware('throttle:10,1');
    });
