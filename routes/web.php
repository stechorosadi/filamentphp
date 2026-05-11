<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

// XML sitemap — no locale prefix needed (for search engines)
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// PWA manifest — dynamic so it reflects site settings (name, colors)
Route::get('/manifest.webmanifest', function () {
    $setting = Cache::remember('site_setting', 300, fn () => SiteSetting::instance());

    return response()->json([
        'name' => $setting->getTranslation('site_title', 'id') ?: config('app.name'),
        'short_name' => $setting->getTranslation('site_title', 'id') ?: config('app.name'),
        'description' => $setting->getTranslation('site_description', 'id') ?? '',
        'start_url' => '/id/',
        'display' => 'standalone',
        'background_color' => $setting->color_light_bg ?? '#ECF39E',
        'theme_color' => $setting->color_accent ?? '#4F772D',
        'icons' => [
            ['src' => '/icons/icon-192.png', 'sizes' => '192x192', 'type' => 'image/png', 'purpose' => 'any'],
            ['src' => '/icons/icon-512.png', 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any'],
        ],
        'screenshots' => [
            [
                'src'         => '/storage/screenshots/desktop-screenshot.png',
                'sizes'       => '1498x903',
                'type'        => 'image/png',
                'form_factor' => 'wide',
                'label'       => $setting->getTranslation('site_title', 'id') ?: config('app.name'),
            ],
            [
                'src'         => '/storage/screenshots/mobile-screenshot.png',
                'sizes'       => '375x798',
                'type'        => 'image/png',
                'form_factor' => 'narrow',
                'label'       => $setting->getTranslation('site_title', 'id') ?: config('app.name'),
            ],
        ],
    ])->header('Content-Type', 'application/manifest+json');
})->name('manifest');

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
            Route::get('/about', [HomeController::class, 'about'])->name('about');
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
