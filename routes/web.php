<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Categories\CategoryController;
use App\Http\Controllers\Product\ProductController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function(){
    Route::resource('categories', CategoryController::class)->except(['create', 'edit', 'show']);
    Route::resource('products', ProductController::class)->except(['create','edit','show']);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
