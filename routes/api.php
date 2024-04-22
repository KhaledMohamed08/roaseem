<?php

use App\Http\Controllers\API\AppSetting\appSettingController;
use App\Http\Controllers\API\Auction\AuctionController;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Chat\ChatController;
use App\Http\Controllers\API\Favorite\FavoriteController;
use App\Http\Controllers\API\Home\HomeController;
use App\Http\Controllers\API\Notification\notificationController;
use App\Http\Controllers\API\Profile\ProfileController;
use App\Http\Controllers\API\Rate\rateController;
use App\Http\Controllers\API\Unit\UnitController;
use App\Http\Controllers\API\Unit\unitFeaturesController;
use App\Http\Controllers\API\UnitReq\unitReqController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\API\VerificationServices\VerificationServiceController;
use App\Http\Responses\ApiResponse;
use App\Models\City;
use App\Models\Country;
use App\Models\Rate;
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
Route::apiResource('auction', AuctionController::class)->except(['store', 'update', 'destroy']);

//app Settings
Route::get('appSetting',[appSettingController::class,'index']);
Route::post('complaintsStore',[appSettingController::class,'complaintsStore']);
Route::get('regulations',[appSettingController::class,'regulations']);
Route::get('News',[appSettingController::class,'news']);
//user Search
Route::get('userSearch',[UserController::class,'search'])->name('userSearch');
Route::get('allMarketers',[UserController::class,'allMarketer'])->name('allmarketer');


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
    // Register Employee For Company
    Route::post('create-employee', [ProfileController::class, 'addMarketerForCompany'])->name('employee.create');
    // Favorite Routes
    Route::get('favorite-toggle/{unitId}', [FavoriteController::class, 'toggleFavorite'])->name('favorite.toggle');
    Route::get('favorites', [FavoriteController::class, 'getFavorites'])->name('favorites');
    // Unit Routes
    Route::apiResource('unit', UnitController::class)->only(['store', 'destroy']);
    Route::post('unit/{unit}', [UnitController::class, 'update']);
    Route::delete('delete-image/{id}', [UnitController::class, 'deleteImage'])->name('image.delete');
    Route::get('unites-types', [ProfileController::class, 'userUnitesStatistics'])->name('unites.types');
    //unitReqs
    Route::get('unit-Reqs',[unitReqController::class,'index'])->name('unitReqs.index');
    Route::post('unitReqs',[unitReqController::class,'store'])->name('unitReqs.store');
    Route::get('unitReqs/{id}',[unitReqController::class,'edit'])->name('unitReqs.edit');
    Route::put('unitReqs',[unitReqController::class,'update'])->name('unitReqs.update');
    Route::delete('unitReqs/{id}',[unitReqController::class,'delete'])->name('unitReqs.destroy');
    Route::get('my-reqs',[unitReqController::class,'myRequests'])->name('myRequests');
    //all Companies
    Route::get('Companies',[UserController::class,'companyFilter'])->name('allCompanies');
    //filter
    Route::post('filter',[unitReqController::class,'filter'])->name('filter');

    // Profile Routes
    Route::get('profile', [ProfileController::class, 'profile'])->name('profile');
    Route::put('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('my-unites', [ProfileController::class, 'myUnites'])->name('my.unites');
    Route::put('reset-password', [ProfileController::class, 'resetPassword'])->name('reset.password');

    // Company Admin Routes
    Route::post('add-marketer', [ProfileController::class, 'addMarketerForCompany'])->name('add.marketer');
    Route::get('company-marketer', [ProfileController::class, 'companyMarketers'])->name('company.marketers');
    Route::get('marketers-numbers', [ProfileController::class, 'companyMarketerssNumbers'])->name('company.marketers.numbers');
    Route::get('active-toggle/{user}', [ProfileController::class, 'marketerActiveToggle'])->name('marketer.active.toggle');
    Route::get('marketers-search', [ProfileController::class, 'companyMarketersSearch'])->name('marketers.search');
    Route::get('show-marketer/{user}', [ProfileController::class, 'showMarketer'])->name('marketer.show');
    Route::post('update-marketer/{user}', [ProfileController::class, 'updateMarketer'])->name('marketer.update');
    Route::delete('delete-marketer/{user}', [ProfileController::class, 'deleteMarketer'])->name('marketer.delete');

    //Notifications
    Route::get('notifications', [notificationController::class, 'index'])->name('notifications.index');
    Route::delete('notifications/{id}', [notificationController::class, 'delete'])->name('notifications.delete');

    //unitFeatures
    Route::get('unitStatus',[unitFeaturesController::class,'getUnitStatus']);
    Route::get('unitStatusFilter',[unitFeaturesController::class,'unitStatusFilter']);
    Route::get('unitTypes',[unitFeaturesController::class,'getUnitType']);
    Route::get('unitService',[unitFeaturesController::class,'getUnitServices']);
    Route::get('unitPurposes',[unitFeaturesController::class,'getunitPurpose']);
    Route::get('unitPayment',[unitFeaturesController::class,'getunitPayment']);
    Route::get('unitInterFace',[unitFeaturesController::class,'getunitInterFace']);

    // Chat
    Route::post('/send-message', [ChatController::class, 'sendMessage']);
    Route::get('/get-messages', [ChatController::class, 'getMessages']);
    Route::get('/get-chats', [ChatController::class, 'getChats']);
    Route::get('/show-chat/{user}', [ChatController::class, 'showChat']);

    //VerificationServices
    Route::get('verificationServices',[VerificationServiceController::class,'index']);
    Route::get('search',[VerificationServiceController::class,'search']);

    //rate
    Route::get('index',[rateController::class,'index']);
    Route::post('createRate',[rateController::class,'store']);
    Route::delete('deleteRate/{id}',[rateController::class,'delete']);

    // Auction Routes
    Route::apiResource('auction', AuctionController::class)->only(['store', 'destroy', 'update']);
    Route::get('my-auctions-orders', [AuctionController::class, 'showMyOrders'])->name('my-auctions');
    Route::post('push-amount/{auction}', [AuctionController::class, 'pushAmountInAuction'])->name('amount.push');
});

// Guest Protected Routes
Route::middleware('guest:sanctum')->group( function () {
    // Auth Routes
    Route::post('register-phone', [AuthController::class, 'generateOTP'])->name('register.phone');
    Route::post('verify-phone', [AuthController::class, 'verifyOtp'])->name('verify.otp');
    Route::post('regenirate-otp', [AuthController::class, 'regenerateOTP'])->name('regenirate-otp');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::put('forget-password', [AuthController::class, 'forgoetPassword'])->name('forget.password');
    Route::put('update-password', [AuthController::class, 'updatePassword'])->name('update.password');
});
