<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\ReviewController;

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

//Route::resource('reviews', ReviewController::class);
Route::prefix('reviews')->group(function(){
    Route::get('', [ReviewController::class, 'index']);
    Route::get('/show/{id}', [ReviewController::class, 'show']);
    Route::post('/create', [ReviewController::class, 'create']);
    Route::put('/update/{id}', [ReviewController::class, 'update']);
    Route::delete('/delete/{id}', [ReviewController::class,'destroy']);
    Route::get('/statistics/{id}', [ReviewController::class, 'statistics']);
    Route::get('/show/user/{id}', [ReviewController::class, 'showuser']);

});

Route::prefix('restaurants')->group(function(){
    Route::get('', [RestaurantController::class, 'index']);
    Route::get('/show/{id}', [RestaurantController::class, 'show']);
    Route::post('/create', [RestaurantController::class, 'create']);
    Route::put('/update/{id}', [RestaurantController::class, 'update']);
    Route::delete('/delete/{id}', [RestaurantController::class,'destroy']);

});


Route::namespace('Api')->group(function(){

    Route::prefix('auth')->group(function(){

        Route::post('login', [AuthController::class, 'login']);
        Route::post('signup', [AuthController::class,'signup']);

    });

    Route::group([
        'middleware'=>'auth:api'
    ], function(){

        Route::get('user', [AuthController::class,'index']);
        Route::post('logout', [AuthController::class,'logout']);

    });

});
