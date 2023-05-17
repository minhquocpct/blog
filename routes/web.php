<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|php artisan route:clear
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('auto-login', 'App\Http\Controllers\AuthController@autoLogin');

Route::group(['middleware'=>'App\Http\Middleware\CheckLoggedinMiddleware'], function(){
    Route::get('login', 'App\Http\Controllers\AuthController@getLogin');
    Route::post('login','App\Http\Controllers\AuthController@postLogin');
    Route::get('signup', 'App\Http\Controllers\AuthController@getSignup');
    Route::post('signup','App\Http\Controllers\AuthController@postSignup');
});

Route::group(['prefix'=>'/','middleware'=>'App\Http\Middleware\LoginMiddleware'],function(){
    Route::get('/', 'App\Http\Controllers\PageController@dashboard');
    Route::get('logout', 'App\Http\Controllers\PageController@logout');
    Route::get('setting', 'App\Http\Controllers\PageController@setting');
    Route::post('setting-acc', 'App\Http\Controllers\PageController@settingAcc');
    Route::post('change-pass','App\Http\Controllers\PageController@changePass');
    Route::post('new-post', 'App\Http\Controllers\PageController@newPost');
    Route::get('share/{id}','App\Http\Controllers\PageController@share');
    Route::post('comment/{id}','App\Http\Controllers\PageController@comment');
    Route::post('search','App\Http\Controllers\PageController@search');
});
