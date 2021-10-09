<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post("register","App\Http\Controllers\UserController@signUp");
Route::post("login","App\Http\Controllers\UserController@login");
Route::group(['middleware'=>'auth:api'],function (){
Route::post("logout", "App\Http\Controllers\UserController@logout");
//Create Message
    Route::post("message/create","App\Http\Controllers\MessageController@insertMessage");
    //Get Messages
    Route::get("message/get","App\Http\Controllers\MessageController@getMessages");
});
