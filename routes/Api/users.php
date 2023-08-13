<?php

use Illuminate\Support\Facades\Route;

Route::post('completeInfo','completeInfo');
//->middleware('verified')

Route::get('specialties/show','updateSpecialty');

Route::post('specialties/edit','editSpecialty');

Route::get('profile/show/{id?}','show');

Route::post('profile/edit','edit');
Route::get('profile/show','showMyProfile');

Route::get('get_profile_posts/{id}','get_profile_posts');
Route::get('get_my_profile_posts','get_my_profile_posts');

Route::get('show_unread_notification','show_unread_notification');
Route::get('show_old_notification','show_old_notification');
