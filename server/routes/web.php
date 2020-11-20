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
| 4) Admin ログイン後
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'cast_admin', 'middleware' => 'auth:cast_admin'], function() {
    Route::post('logout',   'CastAdmin\LoginController@logout')->name('cast_admin.logout');
    Route::get('home',      'CastAdmin\HomeController@index')->name('cast_admin.home');
    Route::post('request_list/movie_upload', 'RequestListController@movieUpload');
    Route::get('request_list/list', 'RequestListController@List');
	// Route::post('request_list/movie_upload', 'CastAdmin\RequestListController@movieUpload');
});