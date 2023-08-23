<?php

use Illuminate\Support\Facades\Route;

Route::post('forgot', 'forgotPassword');

Route::post('checkToken', 'checkToken');

Route::post('reset', 'resetPassword');
