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

// This is the api route that will be called by console grid.
Route::get('/top_picture/', 'GameController@topPictureSearch');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
