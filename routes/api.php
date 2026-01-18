<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Booking\BookingConflictController;
use App\Http\Controllers\Api\Booking\BookingController;
use App\Http\Controllers\Api\Category\CategoryController;
use App\Http\Controllers\Api\Customer\RegisterController;
use App\Http\Controllers\Api\Service\ServiceController;
use App\Http\Controllers\Api\User\UserController;

Route::post('/register', RegisterController::class)
    ->middleware('throttle:5,1');

Route::post('/login', AuthController::class)
    ->middleware('throttle:5,1'); 

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/profile', [UserController::class, 'show']);

    Route::post('/logout', LogoutController::class);

    Route::apiResource('bookings', BookingController::class);
    Route::prefix('bookings/{booking}')->controller(BookingController::class)->group(function () {

        Route::post('/approve', 'approve');
        Route::post('/cancel', 'cancel');

        Route::post('/refund/request', 'requestRefund');
        Route::post('/refund/approve', 'approveRefund');
        Route::post('/refund/deny', 'denyRefund');

        Route::post('/reschedule', 'reschedule');
        Route::post('/no-show', 'markAsNoShow');
    });

    Route::apiResource('categories', CategoryController::class);

    Route::apiResource('services', ServiceController::class);
    // Route::get('/services', [ServiceController::class, 'index']); 

    Route::get('/conflicts', [BookingConflictController::class, 'index'])
            ->name('bookings.conflicts');
});