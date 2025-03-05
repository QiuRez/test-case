<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::patch('/setIsActive', [AdminController::class, 'setIsActive']);