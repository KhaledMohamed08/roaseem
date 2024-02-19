<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Favorite\FavoriteController;
use App\Http\Controllers\API\Home\HomeController;
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

// Global Routes
Route::get('home', [HomeController::class, 'index'])->name('home');

// Auth Protected Routes
Route::middleware('auth:sanctum')->group( function () {
    // Auth Routes
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    // Favorite Routes
    Route::get('favorite-toggle/{unitId}', [FavoriteController::class, 'toggleFavorite'])->name('favorite.toggle');
});

// Guest Protected Routes
Route::middleware('guest:sanctum')->group( function () {
    // Auth Routes
    Route::post('register-phone', [AuthController::class, 'generateOTP'])->name('register.phone');
    Route::post('verify-phone', [AuthController::class, 'verifyOtp'])->name('verify.otp');
    Route::post('regenirate-otp', [AuthController::class, 'regenerateOTP'])->name('regenirate-otp');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
});
