<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);

Route::prefix('category')->group(function(){
    Route::get('/',[CategoryController::class,'index']);
    Route::post('store',[CategoryController::class,'store']);
    Route::get('{id}',[CategoryController::class,'show']);
    Route::put('{id}',[CategoryController::class,'update']);
    Route::delete('{id}',[CategoryController::class,'destroy']);
});