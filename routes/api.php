<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\IngredientsController;
use App\Http\Controllers\Api\RecipesController;
use App\Http\Controllers\Api\CustomersController;

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

    // route resep
    Route::get('{product:slug}/recipes', [RecipesController::class, 'index']);
    Route::post('{product:slug}/recipes', [RecipesController::class, 'store']);
    Route::put('{product:slug}/recipes/{ingredient}', [RecipesController::class, 'update']);
    Route::delete('{product:slug}/recipes/{ingredient}', [RecipesController::class, 'destroy']);
});

Route::prefix('ingredients')->group(function(){
    Route::get('/',[IngredientsController::class,'index']);
    Route::post('/store',[IngredientsController::class,'store']);
    Route::get('/{slug}',[IngredientsController::class,'show']);
    Route::put('/{slug}',[IngredientsController::class,'update']);
    Route::delete('/{slug}',[IngredientsController::class,'destroy']);
});

Route::prefix('customers')->group(function(){
    Route::get('/',[CustomersController::class,'index']);
    Route::post('/store',[CustomersController::class,'store']);
    Route::get('/{slug}',[CustomersController::class,'show']);
    Route::put('/{slug}',[CustomersController::class,'update']);
    Route::delete('/{slug}',[CustomersController::class,'destroy']);
});