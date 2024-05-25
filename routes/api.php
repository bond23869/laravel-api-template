<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BillingController;

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::resource('accounts', AccountController::class);

    Route::post('stripe/create-customer', [BillingController::class, 'createCustomer']);
    Route::post('stripe/charge', [BillingController::class, 'chargeCustomer']);
    Route::post('stripe/create-subscription', [BillingController::class, 'createSubscription']);

    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        // Routes accessible by admin
    });
    
    Route::middleware(['auth:sanctum', 'role:user'])->group(function () {
        // Routes accessible by user
    });

    Route::post('logout', [AuthController::class, 'logout']);
});

Route::get('login/google', [AuthController::class, 'redirectToGoogle']);
Route::get('login/google/callback', [AuthController::class, 'handleGoogleCallback']);
 