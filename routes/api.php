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


Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login');
Route::get('logout', 'AuthController@logout');
Route::post('forgot_password', 'UserController@forgot_password');
Route::get('user', 'AuthController@getAuthUser');

Route::apiResource('products', 'ProductController');
Route::apiResource('categories', 'CategoryController');

