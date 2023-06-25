<?php

use Illuminate\Support\Facades\Route;

Route::post('create_from_profile','create_from_profile')->middleware(['auth:sanctum']);

Route::get('getPost{id}', 'getPost')->middleware(['auth:sanctum']);

Route::get('getallposts', 'getallposts')->middleware(['auth:sanctum']);
