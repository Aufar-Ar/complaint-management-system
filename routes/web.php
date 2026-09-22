<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TechnicianController;
use Illuminate\Support\Facades\Route;

Route::get('/debug-auth', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'user' => auth()->user(),
    ]);
});

// OLD: index.php role redirect
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route(auth()->user()->role === 'employee' ? 'employee.dashboard' : 'technician.dashboard')
        : redirect()->route('login');
});

// Auth Routes — OLD: login.php, register.php, logout.php
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register']);
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

// Employee Routes — OLD: employee/dashboard.php, employee/submit.php
Route::middleware(['auth', 'role:employee'])->group(function () {
    Route::get('/employee/dashboard', [EmployeeController::class, 'dashboard'])->name('employee.dashboard');
    Route::get('/employee/submit',    [EmployeeController::class, 'showSubmit'])->name('employee.submit');
    Route::post('/employee/submit',   [EmployeeController::class, 'submit']);
});

// Technician Routes — OLD: technician/dashboard.php, technician/action.php
Route::middleware(['auth', 'role:technician,admin'])->group(function () {
    Route::get('/technician/dashboard',  [TechnicianController::class, 'dashboard'])->name('technician.dashboard');
    Route::post('/technician/action',    [TechnicianController::class, 'action'])->name('technician.action');
});
