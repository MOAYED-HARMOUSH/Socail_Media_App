<?php

use Illuminate\Support\Facades\Route;

Route::post('create_from_profile','create_from_profile');
Route::post('create_from_community{id}','create_from_community');

Route::get('getMyPosts', 'getMyPosts');
Route::get('getMyCommuites', 'getMyCommuites');
Route::get('gethomeposts', 'gethomeposts');

Route::get('getallposts', 'getallposts');
Route::get('addmedia', 'addmedia');
