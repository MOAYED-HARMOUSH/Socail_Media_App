<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('create_from_profile','create_from_profile')->middleware(['auth:sanctum']);

Route::get('getmedia', 'getmedia')->middleware(['auth:sanctum']);
