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

Route::get('/', function () {
    return view('welcome');
});

/* 사용자 가입 */
Route::post('auth/register', [
	'as' => 'users.store',
	'uses' => 'UsersController@store'
]);

/* 사용자 탈퇴 */
Route::delete('auth/destroy', [
	'as' => 'users.destroy',
	'uses' => 'UsersController@destroy'
]);

/* 사용자 정보 수정 */
Route::patch('auth/update', [
	'as' => 'users.update',
	'uses' => 'UsersController@update'
]);

/* 사용자 인증*/
Route::post('auth/login', [
	'as' => 'sessions.store',
	'uses' => 'SessionsController@store'
]);

/* 사용자 카테고리 즐겨찾기 내역 */
Route::post('auth/favorites/category/call', [
	'as' => 'favoritesCategory.call',
	'uses' => 'FavoritesCategoryController@call'
]);
Route::post('auth/favorites/category/store', [
	'as' => 'favoritesCategory.store',
	'uses' => 'FavoritesCategoryController@store'
]);

/* 사용자 콘텐트 즐겨찾기 내역 */
Route::post('auth/favorites/content/call', [
	'as' => 'favoritesContent.call',
	'uses' => 'FavoritesContentController@call'
]);
Route::post('auth/favorites/content/store', [
	'as' => 'favoritesContent.store',
	'uses' => 'FavoritesContentController@store'
]);

