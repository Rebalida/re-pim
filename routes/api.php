<?php

use App\Http\Controllers\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/stores', [StoreController::class, 'store']);
Route::get('/stores/{store}/check', [StoreController::class, 'check']);