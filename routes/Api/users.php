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
