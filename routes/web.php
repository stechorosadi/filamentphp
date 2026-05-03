<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap', [SitemapController::class, 'html'])->name('sitemap.html');

Route::middleware('throttle:60,1')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/search', [HomeController::class, 'search'])->name('search');
});
Route::middleware('throttle:60,1')->group(function () {
    Route::get('/categories/{slug}', [HomeController::class, 'category'])->name('category.show');
    Route::get('/classifications/{slug}', [HomeController::class, 'classification'])->name('classification.show');
    Route::get('/tags/{slug}', [HomeController::class, 'tag'])->name('tag.show');
    Route::get('/archive', [HomeController::class, 'archive'])->name('archive');
    Route::get('/team', [HomeController::class, 'team'])->name('team');
    Route::get('/team/{member}', [HomeController::class, 'memberShow'])->name('team.member');
    Route::get('/team/{member}/pdf', [HomeController::class, 'memberPdf'])->name('team.member.pdf');
});
Route::get('/articles/{slug}', [HomeController::class, 'show'])->name('content.show')->middleware('throttle:30,1');
Route::get('/articles/{slug}/pdf', [HomeController::class, 'pdf'])->name('content.pdf')->middleware('throttle:10,1');
