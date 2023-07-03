<?php

use Illuminate\Support\Facades\Route;

Route::middleware('Page.MemberOrAdmin')->group(function () {
    Route::get('friends','getFriendsNotMembers');

    Route::post('invitation/send','send');
});

Route::post('invitation/accept','accept');
