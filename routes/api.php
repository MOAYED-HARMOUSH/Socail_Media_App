<?php

use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) { //3
    $request->fulfill();
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

Route::get('createRandomUsers{id}', [UserController::class, 'createRandomUsers']);
Route::get('createRandomSpecialties{id}', [UserController::class, 'createRandomSpecialties']);
Route::get('createRandomPosts{id}', [UserController::class, 'createRandomPosts']);

Route::post('search', [SearchController::class, 'search'])->middleware('auth:sanctum');
Route::get('search/history/show', [SearchController::class, 'index'])->middleware('auth:sanctum');
