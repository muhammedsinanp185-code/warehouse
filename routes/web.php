<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Manager Routes (Warehouse Manager)
Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::get('/manager/dashboard', function () {
        return view('manager.dashboard');
    });
});

// Regular User Routes (Warehouse Employee)
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', function () {
        return view('user.dashboard');
    });
});
