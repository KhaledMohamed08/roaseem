<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Favorite\FavoriteController;
use App\Http\Controllers\API\Home\HomeController;
use App\Http\Controllers\API\Profile\ProfileController;
use App\Http\Controllers\API\Unit\UnitController;
use App\Http\Controllers\API\UnitReq\unitReqController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Responses\ApiResponse;
use App\Models\City;
use App\Models\Country;
use App\Models\Region;
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
Route::apiResource('unit', UnitController::class)->except(['store', 'update', 'destroy']);

Route::get('countries', function () {
    $countries = Country::all();
    return ApiResponse::success(
        [
            'countries' => $countries,
        ]
    );
});
Route::get('cities', function () {
    $cities = City::all();
    return ApiResponse::success(
        [
            'cities' => $cities,
        ]
    );
});
Route::get('regions', function () {
    $regions = Region::all();
    return ApiResponse::success(
        [
            'regions' => $regions,
        ]
    );
});
// Auth Protected Routes
Route::middleware('auth:sanctum')->group( function () {
    // Auth Routes
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    // Favorite Routes
    Route::get('favorite-toggle/{unitId}', [FavoriteController::class, 'toggleFavorite'])->name('favorite.toggle');
    Route::get('favorites', [FavoriteController::class, 'getFavorites'])->name('favorites');
    // Unit Routes
    Route::apiResource('unit', UnitController::class)->only(['store', 'update', 'destroy']);
    Route::delete('delete-image/{id}', [UnitController::class, 'deleteImage'])->name('image.delete');
    //unitReqs
    Route::get('unit-Reqs',[unitReqController::class,'index'])->name('unitReqs.index');
    Route::post('unitReqs',[unitReqController::class,'store'])->name('unitReqs.store');
    Route::get('unitReqs/{id}',[unitReqController::class,'edit'])->name('unitReqs.edit');
    Route::put('unitReqs',[unitReqController::class,'update'])->name('unitReqs.update');
    Route::delete('unitReqs/{id}',[unitReqController::class,'delete'])->name('unitReqs.destroy');
    Route::get('my-reqs',[unitReqController::class,'myRequests'])->name('myRequests');

    //filter Company 
    Route::post('companyFilter',[UserController::class,'companyFilter'])->name('companyFilter');
    // Profile Routes
    Route::get('profile/{user}', [ProfileController::class, 'profile'])->name('profile');
    Route::put('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('my-unites', [ProfileController::class, 'myUnites'])->name('my.unites');
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
