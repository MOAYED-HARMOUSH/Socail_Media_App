<?php

use Illuminate\Support\Facades\Route;

Route::controller('App\Http\Controllers\Api\AuthController')->group(function () {

    Route::post('signUp', 'signUp');

    Route::post('logIn', 'logIn');

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('first', 'hasLoggedIn');

        Route::post('logOut', 'logOut');

        Route::post('signOut', 'deleteAccount');
    });
});

Route::controller('App\Http\Controllers\Api\ResetPasswordController')->group(function () {

    Route::post('forgotPassword', 'forgotPassword');

    Route::post('checkToken', 'checkToken');

    Route::post('resetPassword', 'resetPassword');
});
