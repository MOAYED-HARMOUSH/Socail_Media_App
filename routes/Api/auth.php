<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('signUp','signUp')->name('create');

Route::get('first', 'hasLoggedIn')->middleware(['auth:sanctum']);

Route::post('logIn', 'logIn');

Route::post('logOut','logOut')->middleware('auth:sanctum');

Route::post('signOut','deleteAccount')->middleware('auth:sanctum');

Route::get('getallusers', 'getallusers');
