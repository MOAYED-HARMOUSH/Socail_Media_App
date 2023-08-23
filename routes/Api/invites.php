<?php

use Illuminate\Support\Facades\Route;

Route::middleware('Page.MemberOrAdmin')->group(function () {

    Route::get('friends','getFriendsNotMembers');

    Route::post('invitation/send','send');

    Route::post('invitation/cancel','cancel');
});

Route::post('invitation/accept','accept');

Route::post('invitation/reject','reject');

Route::get('invitation/invitee/show','showInviteeRequest');

Route::get('invitation/inviter/show','showInviterRequest');
