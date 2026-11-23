
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CleaningTaskController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeManagementController;
use App\Http\Controllers\DutyRosterController;
use App\Http\Controllers\AreaController;

// LOGIN

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


// DASHBOARD

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('permission:dashboard.view')
    ->name('dashboard');


// CLEANING TASK MANAGEMENT

Route::get('/cleaning-tasks', [CleaningTaskController::class, 'index'])
    ->middleware('permission:cleaning_tasks.view')
    ->name('cleaning-tasks.index');

Route::get('/cleaning-tasks/create', [CleaningTaskController::class, 'create'])
    ->middleware('permission:cleaning_tasks.add')
    ->name('cleaning-tasks.create');

Route::post('/cleaning-tasks', [CleaningTaskController::class, 'store'])
    ->middleware('permission:cleaning_tasks.add')
    ->name('cleaning-tasks.store');


// EMPLOYEE MANAGEMENT

Route::get('/employee-management', [EmployeeManagementController::class, 'index'])
    ->middleware('permission:users.view|roles.view')
    ->name('employee-management.index');


// USER LIST

Route::post('/employee-management/users', [EmployeeManagementController::class, 'store'])
    ->middleware('permission:users.add')
    ->name('employee-management.users.store');

Route::get('/employee-management/users/{user}/edit', [EmployeeManagementController::class, 'editUser'])
    ->middleware('permission:users.edit')
    ->name('employee-management.users.edit');

Route::put('/employee-management/users/{user}', [EmployeeManagementController::class, 'updateUser'])
    ->middleware('permission:users.edit')
    ->name('employee-management.users.update');

Route::delete('/employee-management/users/{user}', [EmployeeManagementController::class, 'destroyUser'])
    ->middleware('permission:users.delete')
    ->name('employee-management.users.destroy');

Route::delete('/employee-management/users-delete-multiple', [EmployeeManagementController::class, 'destroyMultipleUsers'])
    ->middleware('permission:users.delete')
    ->name('employee-management.users.destroyMultiple');


// ROLE LIST

Route::get('/employee-management/roles/{role}/edit', [EmployeeManagementController::class, 'editRole'])
    ->middleware('permission:roles.edit')
    ->name('employee-management.roles.edit');

Route::put('/employee-management/roles/{role}', [EmployeeManagementController::class, 'updateRole'])
    ->middleware('permission:roles.edit')
    ->name('employee-management.roles.update');

Route::delete('/employee-management/roles/{role}', [EmployeeManagementController::class, 'destroyRole'])
    ->middleware('permission:roles.delete')
    ->name('employee-management.roles.destroy');

Route::delete('/employee-management/roles-delete-multiple', [EmployeeManagementController::class, 'destroyMultipleRoles'])
    ->middleware('permission:roles.delete')
    ->name('employee-management.roles.destroyMultiple');
Route::post('/employee-management/roles', [EmployeeManagementController::class, 'storeRole'])
    ->middleware('permission:roles.add')
    ->name('employee-management.roles.store');
    
// DUTY ROSTER

Route::get('/duty-roster', [DutyRosterController::class, 'index'])
    ->middleware('permission:duty_roster.view')
    ->name('duty-roster.index');

Route::post('/duty-roster', [DutyRosterController::class, 'store'])
    ->middleware('permission:duty_roster.add')
    ->name('duty-roster.store');

Route::put('/duty-roster/{dutyRoster}', [DutyRosterController::class, 'update'])
    ->middleware('permission:duty_roster.edit')
    ->name('duty-roster.update');

Route::delete('/duty-roster/{dutyRoster}', [DutyRosterController::class, 'destroy'])
    ->middleware('permission:duty_roster.delete')
    ->name('duty-roster.destroy');

// AREA MANAGEMENT

Route::get('/areas', [AreaController::class, 'index'])
    ->middleware('permission:areas.view')
    ->name('areas.index');

Route::post('/areas', [AreaController::class, 'store'])
    ->middleware('permission:areas.view')
    ->name('areas.store');

Route::get('/areas/{area}', [AreaController::class, 'show'])
    ->middleware('permission:areas.view')
    ->name('areas.show');