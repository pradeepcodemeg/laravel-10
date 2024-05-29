<?php

use Illuminate\Http\Request;
use App\Http\Controllers\{AuthController, HomeController};
use Illuminate\Support\Facades\{Route};


Route::post('register', [AuthController::class, 'register']);
// verify otp while registration
Route::post('verifyOTP', [AuthController::class, 'verifyOTP']);
Route::post('sendOTP', [AuthController::class, 'sendOTP']);
Route::post('login', [AuthController::class, 'login']);
Route::post('forgot', [AuthController::class, 'forgot']);
Route::post('resetPassword', [AuthController::class, 'resetPassword']);
Route::post('verifyOTPAfterForgot', [AuthController::class, 'verifyOTPAfterForgot']);
Route::post('changePassword', [AuthController::class, 'changePassword']);


// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('getProfile', [HomeController::class, 'getProfile']);
    Route::post('updateProfile', [HomeController::class, 'updateProfile']);
});
