<?php

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//https://accounts.google.com/o/oauth2/auth?client_id=727531366165-f901okekhb03agu39af422rgv97rgitd.apps.googleusercontent.com&redirect_uri=https://localhost&response_type=code&scope=https://www.googleapis.com/auth/calendar&access_type=offline
Route::get('/', function () {

    return "welcomde";
});


Route::get('/oauth/callback', function (Request $req) {
    info("json",[$req]);
    return "done";

});

