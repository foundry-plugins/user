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

Route::prefix('v1')->group(function () {

    Route::post('login', 'UserController@login');
    Route::post('register','UserController@register');
    Route::post('forgot/password','UserController@forgotPassword');
    Route::post('reset/password','UserController@resetPassword');
    Route::get('authenticate', 'UserController@authenticate')->name('authentication_required');

    Route::group(['middleware' => 'auth:api'], function(){

        Route::post('password','UserController@changePassword');

    });
});
