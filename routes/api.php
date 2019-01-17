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

// /api/auth/* path
Route::group([
    'prefix' => 'auth',
], function ()
{
    // public endpoints
    Route::post('signup','AuthController@signup');
    Route::get('activate/{token}','AuthController@preActivate');
    Route::put('activate/{token}','AuthController@activate');
    Route::post('password/recovery','AuthController@recoverPasswordStep1');
    Route::get('password/recovery/{token}','AuthController@recoverPasswordStep2');
    Route::put('password/recovery/{token}','AuthController@resetPassword');
    Route::post('login','AuthController@login');

    // protected endpoints
    Route::group([
        'middleware' => 'auth:api',
    ], function ()
    {
        Route::get('user','AuthController@getUser');
        Route::get('logout','AuthController@logout');
    });
});

// api/users path
Route::group(['prefix' => 'users','middleware' => 'auth:api'],
    function()
    {
        Route::get('/', 'UserController@getUsers');
        Route::get('/{user}', 'UserController@getUser');
        Route::put('/{user}', 'UserController@update');
        Route::put('/{user}/deactivate', 'UserController@deactivate');
        Route::put('/{user}/activate', 'UserController@activate');
    }
);
