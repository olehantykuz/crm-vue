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

Route::group([
    'namespace' => 'Api',
    'middleware' => 'api',
], function () {
    Route::get('currencies', 'CurrencyController@conversation');
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('logout', 'AuthController@logout');
        Route::get('me', 'AuthController@user');
        Route::group(['prefix' => 'transactions'], function () {
            Route::get('/', 'TransactionController@index');
            Route::get('amounts', 'TransactionController@getTotalAmounts');
        });

        Route::group(['prefix' => 'categories'], function () {
            Route::get('/', 'CategoryController@index');
            Route::post('/', 'CategoryController@store');
            Route::put('/{category}', 'CategoryController@update');
            Route::post('/{category}/transaction', 'TransactionController@store');
        });
    });

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
