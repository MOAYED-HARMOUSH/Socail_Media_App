<?php

use App\Http\Controllers\Api\FollowPageController;
use App\Http\Controllers\Api\PageController;
use Illuminate\Support\Facades\Route;

Route::controller(PageController::class)->group(function () {
    Route::post('create', 'create');

    Route::get('index', 'index');

    Route::post('delete', 'destroy');

    Route::post('edit', 'edit');
});

Route::controller(FollowPageController::class)->group(function () {
    Route::post('follow','follow');

    Route::post('unfollow','unfollow');
});
