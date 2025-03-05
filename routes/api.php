<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::resource('posts', PostController::class);
Route::resource('comments', CommentController::class);
Route::resource('users', UserController::class)->middleware('admin');


Route::prefix('users')->name('users.')->group(function() {

    Route::post('register', [UserController::class, 'register'])->name('register');
    Route::post('login', [UserController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function() {
        Route::post('logout', [UserController::class, 'logout'])->name('logout');
    });
});