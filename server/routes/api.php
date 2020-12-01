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

    	//視聴者登録
    	Route::post('/viewer/create', 'ViewerController@apiCreate');
    	Route::post('/viewer/edit', 'ViewerController@apiEdit');
    	Route::post('/viewer/update', 'ViewerController@apiUpdate');
    	Route::post('/viewer/mypage', 'ViewerController@apiMypage');

    	//キャスト一覧
		Route::post('/cast/search', 'CastController@apiSearch');
		Route::post('/cast/detail', 'CastController@apiDetail');

    	//リクエスト
		Route::post('/request_list/create', 'RequestListController@apiCreate');
		Route::get('/request_list/detail', 'RequestListController@apiDetail');

		//お知らせ
		Route::post('/notice/list', 'NoticeController@apiList');
		Route::post('/notice/detail', 'NoticeController@apiDetail');
    });
});