<?php
 
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
 
Auth::routes();
 
/*
|--------------------------------------------------------------------------
| 1) User 認証不要
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
 return redirect('/home'); 
});

/*
|--------------------------------------------------------------------------
| 2) User ログイン後
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'auth:user'], function() {
    Route::get('/home', 'HomeController@index')->name('home');
    // Route::post('/request_list/movie_upload', 'RequestListController@movieUpload');
});
 
/*
|--------------------------------------------------------------------------
| 3) Admin 認証不要
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'admin'], function() {
    Route::get('/',         function () { return redirect('/admin/home'); });
    Route::get('login',     'Admin\LoginController@showLoginForm')->name('admin.login');
    Route::post('login',    'Admin\LoginController@login');
});
 
/*
|--------------------------------------------------------------------------
| 4) Admin ログイン後
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function() {
    Route::post('logout',   'Admin\LoginController@logout')->name('admin.logout');
    Route::get('home',      'Admin\HomeController@index')->name('admin.home');
    //キャスト管理
    Route::get('cast/list', 'CastController@adminList');
    Route::post('cast/search', 'CastController@adminSearch');
    Route::get('cast/create', 'CastController@adminCreate');
    Route::post('cast/confirm', 'CastController@adminConfirm');
    Route::post('cast/complete', 'CastController@adminComplete');
    Route::get('cast/edit', 'CastController@adminEdit');
    Route::post('cast/update', 'CastController@adminUpdate');
    Route::get('cast/detail', 'CastController@adminDetail');

    //視聴者管理
    Route::get('viewer/list', 'ViewerController@adminList');
    Route::post('viewer/create', 'ViewerController@adminCreate');
    Route::get('viewer/edit', 'ViewerController@adminEdit');
    Route::post('viewer/update', 'ViewerController@adminUpdate');
    Route::get('viewer/detail', 'ViewerController@adminDetail');

    //リクエスト管理
    Route::get('request_list/list', 'RequestListController@adminList');
    Route::post('request_list/search', 'RequestListController@adminSearch');
    Route::get('request_list/edit', 'RequestListController@adminEdit');
    Route::post('request_list/update', 'RequestListController@adminUpdate');
    Route::get('request_list/detail', 'RequestListController@adminDetail');

    //お知らせ管理
    Route::get('notice/list', 'NoticeController@adminList');
    Route::post('notice/search', 'NoticeController@adminSearch');
    Route::get('notice/edit', 'NoticeController@adminEdit');
    Route::post('notice/update', 'NoticeController@adminUpdate');
    Route::get('notice/create', 'NoticeController@adminCreate');
    Route::get('notice/confirm', 'NoticeController@adminConfirm');
    Route::get('notice/complete', 'NoticeController@adminComplete');

    //支払い管理
    Route::get('withdraw/list', 'WithdrawController@adminList');
    Route::post('withdraw/search', 'WithdrawController@adminSearch');
    Route::get('withdraw/detail', 'WithdrawController@adminDetail');
});

/*
|--------------------------------------------------------------------------
| 3) CastAdmin 認証不要
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'cast_admin'], function() {
    Route::get('/',         function () { return redirect('/cast_admin/home'); });
    Route::get('login',     'CastAdmin\LoginController@showLoginForm')->name('cast_admin.login');
    Route::post('login',    'CastAdmin\LoginController@login');
});
/*
|--------------------------------------------------------------------------
| 4) CastAdmin ログイン後
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'cast_admin', 'middleware' => 'auth:cast_admin'], function() {
    Route::post('logout', 'CastAdmin\LoginController@logout')->name('cast_admin.logout');
    Route::get('home', 'CastAdmin\HomeController@index')->name('cast_admin.home');
    //リクエスト
    Route::post('request_list/movie_upload', 'RequestListController@castAdminMovieUpload');
    Route::get('request_list/list', 'RequestListController@castAdminList');
	Route::get('request_list/detail', 'RequestListController@castAdminDetail');
    Route::post('request_list/search', 'RequestListController@castAdminSearch');
    
	//キャストプロフィール
	Route::post('cast/create', 'CastController@castAdminCreate');
	Route::get('cast/edit', 'CastController@castAdminEdit');
	Route::post('cast/update', 'CastController@castAdminUpdate');
	Route::get('cast/detail', 'CastController@castAdminDetail');

    //お知らせ管理
    Route::get('notice/list', 'NoticeController@castAdminList');
    Route::get('notice/detail', 'NoticeController@castAdminDetail');

});