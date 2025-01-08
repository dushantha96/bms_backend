<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\SpotsController;
use App\Http\Controllers\API\BookingsController;
use App\Http\Controllers\API\ReviewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'JwtMiddleware'], function () {
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('profile', [AuthController::class,'updateProfile']);
    Route::get('profile', [AuthController::class,'getProfile']);
    
    Route::post('booking/place', [BookingsController::class,'place']);
    Route::get('booking/user', [BookingsController::class,'getByUser']);

    Route::get('spot/user', [SpotsController::class,'getByUser']);
    Route::post('spot', [SpotsController::class,'save']);
    Route::delete('spot/{id}', [SpotsController::class,'delete']);
    
    Route::post('review/rate', [ReviewsController::class,'rate']);
});

Route::post('login', [AuthController::class,'login']);
Route::post('signup', [AuthController::class,'signup']);

Route::post('spot/search', [SpotsController::class,'search']);
Route::get('spot/details', [SpotsController::class,'details']);