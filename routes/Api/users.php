<?php

use Illuminate\Support\Facades\Route;

Route::get('getAvatar', 'getAvatar');

Route::post('completeInfo','completeInfo');
Route::post('Editspecialty','Editspecialty');
//->middleware('verified')
