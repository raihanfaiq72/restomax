<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Categories\CategoryController;

Route::middleware('auth')->group(function(){
    Route::resource('category', CategoryController::class)->except([
        'create', 'edit', 'show'
    ]);
});