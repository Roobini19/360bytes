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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    // 'middleware' => 'auth:api',
    'prefix' => 'auth',
],
    function($router) {
        Route::post('login', 'AuthController@login');
        Route::post('register', 'AuthController@register');
        Route::get('user-profile', 'AuthController@userProfile');
        Route::post('requestloan', 'LoanController@loanRequest');
        Route::post('loan/pay', 'LoanController@loanPay');
        Route::post('paymentamount', 'LoanController@getPaymentAmount');
        Route::get('logout', 'AuthController@logout');
    }
);
