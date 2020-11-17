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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(["middleware" => "api"], function () {
    // 認証が必要ないメソッド
    Route::post('/register', 'UserController@register'); // 追加
    Route::post('/login', 'Auth\LoginController@login');　// 追加
    Route::post('user/init', 'UserController@userInit');//ユーザー初期化API

    Route::post("/password/email", "Auth\ForgotPasswordController@sendResetLinkEmail");
    Route::post("/password/reset/{token}", "Auth\ResetPasswordController@reset");
    Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify'); // 追加
    Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend'); // 追加

    Route::group(['middleware' => ['jwt.auth']], function () {
    // 認証が必要なメソッド

    });

});