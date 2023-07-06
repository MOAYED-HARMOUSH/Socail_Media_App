<?php

use Illuminate\Support\Facades\Route;

Route::post('forgotPassword', 'forgotPassword');

Route::post('checkToken', 'checkToken');

Route::post('resetPassword', 'resetPassword');
