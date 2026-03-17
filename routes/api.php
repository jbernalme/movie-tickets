<?php

use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('discounts', DiscountController::class);
Route::post('discounts/validate', [DiscountController::class, 'validate']);
Route::post('tickets', [TicketController::class, 'store']);
