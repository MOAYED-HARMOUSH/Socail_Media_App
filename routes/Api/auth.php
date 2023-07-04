<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {

    Route::get('getallusers', 'getallusers');

    Route::post('signUp', 'signUp');

    Route::post('logIn', 'logIn');

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('first', 'hasLoggedIn');

        Route::post('logOut', 'logOut');

        Route::post('signOut', 'deleteAccount');
    });
});

Route::controller(ResetPasswordController::class)->group(function () {

    Route::post('forgotPassword', 'forgotPassword');

    Route::post('checkToken', 'checkToken');

    Route::post('resetPassword', 'resetPassword');
});
