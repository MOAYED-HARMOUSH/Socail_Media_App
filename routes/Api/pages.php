<?php

use Illuminate\Support\Facades\Route;

Route::post('create', 'create');

Route::get('index', 'index');

Route::post('delete', 'destroy');

Route::post('edit', 'edit');
