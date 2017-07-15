<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', 'MainController');

Route::get('/console/{id}', 'ConsoleController@listGames');

Route::get('/pictures/vote/{id}/{action}', 'PictureController@vote');

Route::get('/games/{id}', 'GameController@find');

Route::get('/games', 'GameController@search');

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Auth::routes();


