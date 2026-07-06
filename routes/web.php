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

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SettingController;

// Manager Routes (Warehouse Manager)
Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::get('/manager/dashboard', [DashboardController::class, 'managerIndex'])->name('manager.dashboard');
    
    // Products
    Route::get('/manager/products', [ProductController::class, 'index'])->name('manager.products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    
    // Inventory
    Route::get('/manager/inventory', [InventoryController::class, 'index'])->name('manager.inventory');
    Route::post('/inventory/receive', [InventoryController::class, 'receive'])->name('inventory.receive');
    Route::post('/inventory/dispatch', [InventoryController::class, 'dispatch'])->name('inventory.dispatch');
    Route::delete('/inventory/{movement}', [InventoryController::class, 'destroy'])->name('inventory.destroy');

    // Settings & User Management
    Route::get('/manager/settings', function() {
        return redirect()->route('settings.profile');
    })->name('manager.settings');
    Route::get('/manager/settings/profile', [SettingController::class, 'profile'])->name('settings.profile');
    Route::get('/manager/settings/security', [SettingController::class, 'security'])->name('settings.security');
    Route::get('/manager/settings/team', [SettingController::class, 'team'])->name('settings.team');
    
    Route::put('/manager/settings/profile', [SettingController::class, 'updateProfile'])->name('settings.profile.update');
    Route::put('/manager/settings/password', [SettingController::class, 'updatePassword'])->name('settings.password');
    Route::post('/manager/settings/employees', [SettingController::class, 'storeEmployee'])->name('settings.employees.store');
    Route::delete('/manager/settings/employees/{employee}', [SettingController::class, 'destroyEmployee'])->name('settings.employees.destroy');
});

use App\Http\Controllers\UserDashboardController;

// Regular User Routes (Warehouse Employee)
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('user.dashboard');
    });
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::post('/user/inventory/receive', [InventoryController::class, 'receive'])->name('user.inventory.receive');
    Route::post('/user/inventory/dispatch', [InventoryController::class, 'dispatch'])->name('user.inventory.dispatch');
});
