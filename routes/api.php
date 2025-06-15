<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\IngredientsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);

Route::prefix('category')->group(function(){
    Route::get('/',[CategoryController::class,'index']);
    Route::post('/store',[CategoryController::class,'store']);
    Route::get('/{slug}',[CategoryController::class,'show']);
    Route::put('/{slug}',[CategoryController::class,'update']);
    Route::delete('/{slug}',[CategoryController::class,'destroy']);
});

Route::prefix('product')->group(function(){
    Route::get('/',[ProductController::class,'index']);
    Route::post('/store',[ProductController::class,'store']);
    Route::get('/{slug}',[ProductController::class,'show']);
    Route::put('/{slug}',[ProductController::class,'update']);
    Route::delete('/{slug}',[ProductController::class,'destroy']);
});

Route::prefix('ingredients')->group(function(){
    Route::get('/',[IngredientsController::class,'index']);
    Route::post('/store',[IngredientsController::class,'store']);
    Route::get('/{slug}',[IngredientsController::class,'show']);
    Route::put('/{slug}',[IngredientsController::class,'update']);
    Route::delete('/{slug}',[IngredientsController::class,'destroy']);
});