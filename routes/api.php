<?php

use App\Http\Controllers\Api\DiscountController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('discounts', DiscountController::class);
Route::post('discounts/validate', [DiscountController::class, 'validate']);
