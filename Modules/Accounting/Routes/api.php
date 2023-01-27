<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Accounting\Http\Controllers\ApiAuthController;

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

Route::prefix('v1')->group(function () {

Route::post('/login', [ApiAuthController::class,"login"]);
Route::post('/register', [ApiAuthController::class,"register"]);
Route::post('/update_res_google',[ApiAuthController::class,"update_res_google"]);

Route::middleware('auth:api')->group(function () {
    Route::get('/logout', [ApiAuthController::class,"logout"]);
});

});
