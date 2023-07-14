<?php

use Illuminate\Support\Facades\Route;

Route::post('create_from_profile','create_from_profile')->middleware(['auth:sanctum']);
Route::post('create_from_community{id}','create_from_community')->middleware(['auth:sanctum']);
Route::post('create_from_page{id}','create_from_page')->middleware(['auth:sanctum']);
Route::get('like_or_cancellike_on_post/{post_id}','like_or_cancellike_on_post')->middleware(['auth:sanctum']);
Route::get('dislike_or_canceldislike_on_post/{post_id}','dislike_or_canceldislike_on_post')->middleware(['auth:sanctum']);
Route::post('create_comment_on_post/{post_id}','create_comment_on_post')->middleware(['auth:sanctum']);
Route::get('get_comments_on_post/{post_id}', 'get_comments_on_post')->middleware(['auth:sanctum']);

Route::get('like_or_cancellike_on_comment/{comment_id}','like_or_cancellike_on_comment')->middleware(['auth:sanctum']);
Route::get('dislike_or_canceldislike_on_comment/{comment_id}','dislike_or_canceldislike_on_comment')->middleware(['auth:sanctum']);

Route::get('report_or_cancelreport_on_post/{post_id}','report_or_cancelreport_on_post')->middleware(['auth:sanctum']);
Route::get('report_or_cancelreport_on_comment/{post_id}','report_or_cancelreport_on_comment')->middleware(['auth:sanctum']);


Route::get('getMyPosts', 'getMyPosts')->middleware(['auth:sanctum']);
Route::get('getMyCommuites', 'getMyCommuites')->middleware(['auth:sanctum']);
Route::get('getMyPagesasfollow', 'getMyPagesasfollow')->middleware(['auth:sanctum']);
Route::get('gethomeposts', 'gethomeposts')->middleware(['auth:sanctum']);

Route::get('getallposts', 'getallposts');
Route::get('addmedia', 'addmedia')->middleware(['auth:sanctum']);
