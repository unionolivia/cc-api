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

Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function () {

	Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function () {
		Route::post('register', 'Api\UserController@register');
		Route::post('login', 'Api\UserController@login');
		Route::post('forgot_password', 'Api\UserController@forgot_password');
	});

	 Route::middleware(['auth:api'])->group(function () {
	 
		Route::get('logout', 'Api\AuthController@logout');
		Route::get('user', 'Api\AuthController@getAuthUser');

		// Email verification
		Route::get('/email/resend', 'Api\VerificationController@resend')->name('verification.resend');
		Route::get('/email/verify/{id}', 'Api\VerificationController@verify')->name('verification.verify');

		Route::apiResource('products', 'Api\ProductController');
		Route::apiResource('categories', 'Api\CategoryController');
	}):	
	
	// no need auth:api middleware, because if token expired we need to refresh it
    Route::post('refresh', 'AuthController@refresh');
});
