<?php

use Illuminate\Support\Facades\Route;

Route::get('getallusers', 'getallusers'); //For Test Only

Route::post('signUp', 'signUp');

Route::post('logIn', 'logIn');

Route::middleware('auth:sanctum')->group(function () {

    Route::get('first', 'hasLoggedIn');

    Route::post('logOut', 'logOut');

    Route::post('signOut', 'deleteAccount');
});
