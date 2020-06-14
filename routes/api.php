<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@Login');
    Route::post('register', 'AuthController@Register');
    Route::get('signup/activate/{token}', 'AuthController@signupActivate');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', 'AuthController@Logout');
        Route::get('user', 'AuthController@User');
    });
});


/**
 * Reset Password Routes
 */
Route::group(['prefix' => 'password', 'middleware' => 'auth:api'], function () {
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');
});


Route::group(['middleware' => 'auth:api'], function () {

    Route::group(['prefix' => 'faculty'], function () {
        // Fetch Faculty Data
        Route::get('/{id?}', 'FacultyController@Find');

        // Create New Faculty
        Route::post('/', 'FacultyController@Create');

        


        
    });
});