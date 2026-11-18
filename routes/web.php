<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CleaningTaskController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('login');
});

Route::get('/login', function () {
    return view('login');
});
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::get('/forgot-password', function () {
    return view('forgot-password');
});

Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/cleaning-tasks', [CleaningTaskController::class, 'index'])
    ->name('cleaning-tasks.index');
Route::get('/cleaning-tasks/create', [CleaningTaskController::class, 'create'])
    ->name('cleaning-tasks.create');
Route::post('/cleaning-tasks', [CleaningTaskController::class, 'store'])
    ->name('cleaning-tasks.store');
