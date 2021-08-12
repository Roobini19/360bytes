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

Route::group([
    'middleware' => 'admin'
],
    function($router) {
        Route::post('login', 'AdminController@login');
        Route::get('profile', 'AdminController@profile');
        Route::get('loanlist', 'AdminController@loanList');
        Route::post('loan/approve/{id}', 'AdminController@loanApprove');
        Route::post('loan/reject/{id}', 'AdminController@loanReject');
    }
);
