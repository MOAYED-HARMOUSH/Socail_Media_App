<?php

use Illuminate\Support\Facades\Route;

Route::post('request/send', 'send');

Route::post('request/accept', 'accept');

Route::post('request/reject', 'reject');

Route::get('request/receiver/show','showReceiverRequest');

Route::get('request/sender/show','showSenderRequest');

Route::get('request/rejected/show','showRejectedRequests');

Route::get('show','showFriends');
