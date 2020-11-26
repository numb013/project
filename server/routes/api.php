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

Route::group(["middleware" => "api"], function () {
	Route::post('/login', 'Auth\LoginController@login');
    Route::post('/register', 'Auth\RegisterController@register'); // 追加
    Route::group(['middleware' => ['jwt.auth']], function () {
    	Route::post('/home', 'ApiController@index');
    });
});