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
// Public
Route::post('signup','AuthController@signup');
Route::get('activate/{token}','AuthController@preActivate');
Route::put('activate/{token}','AuthController@activate');
Route::post('password/recovery','AuthController@recoverPasswordStep1');
Route::get('password/recovery/{token}','AuthController@recoverPasswordStep2');
Route::put('password/recovery/{token}','AuthController@resetPassword');
Route::post('login','AuthController@login');


// Authenticated
Route::group([
    'middleware' => 'auth:api',
], function ()
{
    // All users
    Route::get('myprofile','AuthController@getUser');
    Route::get('logout','AuthController@logout');

    // Role-based

    // Administration
    Route::group([
        'prefix' => 'admin',
        'middleware' => 'roles',
        'roles' => 'Administrator'
        ],
        function()
        {
            // Users
            Route::get('/users', 'UserController@getUsers');
            Route::get('users/{user}', 'UserController@getUser');
            Route::put('users/{user}', 'UserController@update');
            Route::put('users/{user}/deactivate', 'UserController@deactivate');
            Route::put('users/{user}/activate', 'UserController@activate');
        }
    );
});

// Tests
Route::group([
    'prefix' => 'test/roles',
    'middleware' => ['auth:api','roles']
], function()
{
    Route::group([
        'roles' => 'Administrator'
    ], function()
    {
        Route::get('isadmin', 'TestController@isAdmin');
    });

    Route::group([
        'roles' => 'Head'
    ], function()
    {
        Route::get('ishead', 'TestController@isHead');
    });

    Route::group([
        // 'roles' => 'Any'
    ], function()
    {
        Route::get('isany', 'TestController@isAny');
    });
});
