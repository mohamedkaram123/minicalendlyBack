<?php

use Illuminate\Http\Request;

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

Route::prefix('v1/events')->group(function () {

    Route::middleware('auth:api')->group(function () {
    Route::post('/store',"EventsController@store");
    Route::post('/update',"EventsController@update");
    Route::get('/locations',"EventsController@get_all_locations");
    Route::get('/events_user',"EventsController@events_user");
    Route::post('/update_level1',"EventsController@update_level1");
    Route::post('/turn_event',"EventsController@turn_event");


    });
    Route::post('/get_all_times',"EventsController@get_all_times");
    Route::post('/get_event',"EventsController@get_event");
    Route::post('/event_confirm_store',"EventsController@event_confirm_store");




});
