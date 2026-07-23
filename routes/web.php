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
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;

// Manager Routes (Warehouse Manager)
Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::get('/manager/dashboard', [DashboardController::class, 'managerIndex'])->name('manager.dashboard');
    Route::get('/manager/api/chart-data', [DashboardController::class, 'chartData'])->name('manager.chart.data');
    
    // Products
    Route::get('/manager/products', [ProductController::class, 'index'])->name('manager.products.index');
    Route::get('/manager/products/{product}', [ProductController::class, 'show'])->name('manager.products.show');
    Route::get('/manager/products/{product}/history', [ProductController::class, 'history'])->name('manager.products.history');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Categories
    Route::get('/manager/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Brands
    Route::get('/manager/brands', [BrandController::class, 'index'])->name('brands.index');
    Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
    Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
    Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');
    
    // Vendors
    Route::get('/manager/vendors', [\App\Http\Controllers\VendorController::class, 'index'])->name('vendors.index');
    Route::post('/vendors', [\App\Http\Controllers\VendorController::class, 'store'])->name('vendors.store');
    Route::put('/vendors/{vendor}', [\App\Http\Controllers\VendorController::class, 'update'])->name('vendors.update');
    Route::delete('/vendors/{vendor}', [\App\Http\Controllers\VendorController::class, 'destroy'])->name('vendors.destroy');

    // Customers
    Route::get('/manager/customers', [\App\Http\Controllers\CustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers', [\App\Http\Controllers\CustomerController::class, 'store'])->name('customers.store');
    Route::put('/customers/{customer}', [\App\Http\Controllers\CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [\App\Http\Controllers\CustomerController::class, 'destroy'])->name('customers.destroy');
    
    // Reports Center & Individual Reports
    Route::get('/manager/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('manager.reports.index');
    Route::get('/manager/reports/sales-by-customer', [App\Http\Controllers\ReportController::class, 'salesByCustomer'])->name('manager.reports.sales-by-customer');
    Route::get('/manager/reports/sales-by-item', [App\Http\Controllers\ReportController::class, 'salesByItem'])->name('manager.reports.sales-by-item');
    Route::get('/manager/reports/purchases-by-vendor', [App\Http\Controllers\ReportController::class, 'purchasesByVendor'])->name('manager.reports.purchases-by-vendor');
    Route::get('/manager/reports/order-fulfillment', [App\Http\Controllers\ReportController::class, 'orderFulfillment'])->name('manager.reports.order-fulfillment');
    Route::get('/manager/reports/packing-history', [App\Http\Controllers\ReportController::class, 'packingHistory'])->name('manager.reports.packing-history');
    Route::get('/manager/reports/valuation', [InventoryController::class, 'report'])->name('manager.inventory-report');
    Route::get('/manager/reports/low-stock', [InventoryController::class, 'lowStock'])->name('manager.low-stock');
    
    // Inventory Operations
    Route::get('/manager/inventory', [InventoryController::class, 'index'])->name('manager.inventory');
    Route::get('/manager/inventory/received', [InventoryController::class, 'receivedHistory'])->name('manager.inventory.received');
    Route::get('/manager/inventory/dispatched', [InventoryController::class, 'dispatchedHistory'])->name('manager.inventory.dispatched');
    Route::post('/inventory/receive', [InventoryController::class, 'receive'])->name('inventory.receive');
    Route::post('/inventory/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');
    Route::post('/inventory/dispatch', [InventoryController::class, 'dispatch'])->name('inventory.dispatch');

    // Purchase Orders
    Route::get('/manager/purchase-orders', [\App\Http\Controllers\PurchaseOrderController::class, 'index'])->name('manager.purchase-orders.index');
    Route::get('/manager/purchase-orders/create', [\App\Http\Controllers\PurchaseOrderController::class, 'create'])->name('manager.purchase-orders.create');
    Route::post('/manager/purchase-orders', [\App\Http\Controllers\PurchaseOrderController::class, 'store'])->name('manager.purchase-orders.store');
    Route::get('/manager/purchase-orders/{purchaseOrder}', [\App\Http\Controllers\PurchaseOrderController::class, 'show'])->name('manager.purchase-orders.show');
    Route::get('/manager/purchase-orders/{purchaseOrder}/pdf', [\App\Http\Controllers\PurchaseOrderController::class, 'downloadPdf'])->name('manager.purchase-orders.pdf');
    Route::get('/manager/purchase-orders/{purchaseOrder}/pick-list', [\App\Http\Controllers\PurchaseOrderController::class, 'downloadPickList'])->name('manager.purchase-orders.pick-list');
    Route::post('/manager/purchase-orders/{purchaseOrder}/receive', [\App\Http\Controllers\PurchaseOrderController::class, 'receive'])->name('manager.purchase-orders.receive');
    Route::delete('/inventory/{movement}', [InventoryController::class, 'destroy'])->name('inventory.destroy');

    // Sales Orders (Phase 2)
    Route::get('/manager/sales-orders', [\App\Http\Controllers\SalesOrderController::class, 'index'])->name('manager.sales_orders.index');
    Route::get('/manager/sales-orders/create', [\App\Http\Controllers\SalesOrderController::class, 'create'])->name('manager.sales_orders.create');
    Route::post('/manager/sales-orders', [\App\Http\Controllers\SalesOrderController::class, 'store'])->name('manager.sales_orders.store');
    Route::get('/manager/sales-orders/{salesOrder}', [\App\Http\Controllers\SalesOrderController::class, 'show'])->name('manager.sales_orders.show');
    Route::post('/manager/sales-orders/{salesOrder}/confirm', [\App\Http\Controllers\SalesOrderController::class, 'confirm'])->name('manager.sales_orders.confirm');

    // Shipments (Phase 2)
    Route::get('/manager/shipments', [\App\Http\Controllers\ShipmentController::class, 'index'])->name('manager.shipments.index');
    Route::post('/manager/shipments/{salesOrder}', [\App\Http\Controllers\ShipmentController::class, 'store'])->name('manager.shipments.store');
    Route::post('/manager/shipments/{shipment}/ship', [\App\Http\Controllers\ShipmentController::class, 'ship'])->name('manager.shipments.ship');
    Route::post('/manager/shipments/{shipment}/deliver', [\App\Http\Controllers\ShipmentController::class, 'deliver'])->name('manager.shipments.deliver');

    // Settings & User Management
    Route::get('/manager/settings', function() {
        return redirect()->route('settings.profile');
    })->name('manager.settings');
    Route::get('/manager/settings/profile', [SettingController::class, 'profile'])->name('settings.profile');
    Route::get('/manager/settings/security', [SettingController::class, 'security'])->name('settings.security');
    Route::get('/manager/settings/team', [SettingController::class, 'team'])->name('settings.team');
    
    // Notifications
    Route::post('/manager/notifications/mark-all-read', function() {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.markAllRead');
    
    Route::delete('/manager/notifications/clear-all', function() {
        auth()->user()->notifications()->delete();
        return back();
    })->name('notifications.clearAll');

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
    
    // User Workspace Expansions
    Route::get('/user/products', [UserDashboardController::class, 'products'])->name('user.products.index');
    Route::get('/user/products/{product}', [UserDashboardController::class, 'productShow'])->name('user.products.show');
    Route::get('/user/activity', [UserDashboardController::class, 'activity'])->name('user.activity');
    
    // Shift Routes
    Route::post('/user/shift/start', [UserDashboardController::class, 'startShift'])->name('user.shift.start');
    Route::post('/user/shift/end', [UserDashboardController::class, 'endShift'])->name('user.shift.end');

    // Inventory actions
    Route::post('/user/inventory/receive', [InventoryController::class, 'receive'])->name('user.inventory.receive');
    Route::post('/user/inventory/dispatch', [InventoryController::class, 'dispatch'])->name('user.inventory.dispatch');
    Route::delete('/user/inventory/{movement}', [InventoryController::class, 'destroy'])->name('user.inventory.destroy');
});
