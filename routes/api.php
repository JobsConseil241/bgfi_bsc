<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes OTP avec Airtel (API)
Route::prefix('otp')->name('api.otp.')->group(function () {
    Route::post('/send', [\App\Http\Controllers\OtpController::class, 'send'])->name('send');
    Route::post('/verify', [\App\Http\Controllers\OtpController::class, 'verify'])->name('verify');
    Route::post('/check', [\App\Http\Controllers\OtpController::class, 'check'])->name('check');
    Route::post('/invalidate', [\App\Http\Controllers\OtpController::class, 'invalidate'])->name('invalidate');
});

// Route pour envoyer un SMS normal via Airtel (API)
Route::post('/airtel/sms', [\App\Http\Controllers\SendSMSController::class, 'sendAirtelSms'])->name('api.airtel.sms');
