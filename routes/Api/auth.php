<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('signUp','signUp')->name('create');

Route::post('first', 'hasLoggedIn')->middleware('auth:sanctum');
