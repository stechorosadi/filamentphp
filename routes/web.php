<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:60,1')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/search', [HomeController::class, 'search'])->name('search');
});
Route::middleware('throttle:60,1')->group(function () {
    Route::get('/categories/{slug}', [HomeController::class, 'category'])->name('category.show');
    Route::get('/classifications/{slug}', [HomeController::class, 'classification'])->name('classification.show');
    Route::get('/team', [HomeController::class, 'team'])->name('team');
    Route::get('/team/{member}', [HomeController::class, 'memberShow'])->name('team.member');
});
Route::get('/articles/{slug}', [HomeController::class, 'show'])->name('content.show')->middleware('throttle:30,1');
Route::get('/articles/{slug}/pdf', [HomeController::class, 'pdf'])->name('content.pdf')->middleware('throttle:10,1');
