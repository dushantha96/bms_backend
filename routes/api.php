<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\SpotsController;
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
});

Route::post('login', [AuthController::class,'login']);
Route::post('signup', [AuthController::class,'signup']);

Route::post('filter/map', [SpotsController::class,'filterMap']);
Route::post('filter/list', [SpotsController::class,'filterList']);