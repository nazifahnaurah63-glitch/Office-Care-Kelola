<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CleaningTaskController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CleaningActivityController;

Route::get('/', function () {
    return redirect()->route('dashboard'); // Membutuhkan name('dashboard')
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::get('/forgot-password', function () {
    return view('forgot-password');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

// SEMUA HALAMAN SETELAH LOGIN DIKELOMPOKKAN DISINI
Route::middleware(['auth'])->group(function () {
    // Dashboard (Ditambahkan ->name('dashboard'))
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Cleaning Tasks
    Route::get('/cleaning-tasks', [CleaningTaskController::class, 'index'])
        ->name('cleaning-tasks.index');
    Route::get('/cleaning-tasks/create', [CleaningTaskController::class, 'create'])
        ->name('cleaning-tasks.create');
    Route::post('/cleaning-tasks', [CleaningTaskController::class, 'store'])
        ->name('cleaning-tasks.store');

    // Cleaning Activities
    Route::get('/cleaning-activities', [CleaningActivityController::class, 'index'])
        ->name('cleaning-activities.index');

    Route::post('/cleaning-activities', [CleaningActivityController::class, 'store'])
        ->name('cleaning-activities.store');

    Route::put('/cleaning-activities/{cleaningActivity}', [CleaningActivityController::class, 'update'])
        ->name('cleaning-activities.update');

    // URUTAN PENTING: bulk-destroy harus di ATAS route parameter {cleaningActivity}
    Route::delete('/cleaning-activities/bulk-destroy', [CleaningActivityController::class, 'bulkDestroy'])
        ->name('cleaning-activities.bulk-destroy');

    Route::delete('/cleaning-activities/{cleaningActivity}', [CleaningActivityController::class, 'destroy'])
        ->name('cleaning-activities.destroy');
});