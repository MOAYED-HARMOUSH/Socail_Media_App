<?php

use Illuminate\Support\Facades\Route;

Route::post('create_from_profile','create_from_profile')->middleware(['auth:sanctum']);
Route::post('create_from_community{id}','create_from_community')->middleware(['auth:sanctum']);

Route::get('getMyPosts', 'getMyPosts')->middleware(['auth:sanctum']);
Route::get('getMyCommuites', 'getMyCommuites')->middleware(['auth:sanctum']);
Route::get('gethomeposts', 'gethomeposts')->middleware(['auth:sanctum']);

Route::get('getallposts', 'getallposts')->middleware(['auth:sanctum']);
Route::get('addmedia', 'addmedia')->middleware(['auth:sanctum']);
