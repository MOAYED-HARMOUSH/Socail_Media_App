<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('signUp','signUp')->name('create');

Route::get('first', 'hasLoggedIn')->middleware(['auth:sanctum']);
