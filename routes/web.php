<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/categories/{slug}', [HomeController::class, 'category'])->name('category.show');
Route::get('/classifications/{slug}', [HomeController::class, 'classification'])->name('classification.show');
Route::get('/articles/{slug}', [HomeController::class, 'show'])->name('content.show');
