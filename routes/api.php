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


Route::post('register', 'Api\UserController@register');
Route::post('login', 'Api\UserController@login');
Route::get('logout', 'Api\AuthController@logout');
Route::post('forgot_password', 'Api\UserController@forgot_password');
Route::get('user', 'Api\AuthController@getAuthUser');

Route::get('/email/resend', 'Api\VerificationController@resend')->name('verification.resend');
Route::get('/email/verify/{id}/{hash}', 'Api\VerificationController@verify')->name('verification.verify');

Route::apiResource('products', 'Api\ProductController');
Route::apiResource('categories', 'Api\CategoryController');

