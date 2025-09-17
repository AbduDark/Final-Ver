<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Cashier\{CashierDashboardController, InvoiceController};
use App\Http\Controllers\SuperAdmin\SuperAdminDashboardController;
use App\Http\Controllers\Admin\{ProductController, CategoryController, ReportController};
use Illuminate\Support\Facades\Route;

// Routes المصادقة
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes السوبر أدمن
Route::prefix('superadmin')->middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('superadmin.dashboard');
    
    // إدارة المتاجر
    Route::resource('stores', \App\Http\Controllers\SuperAdmin\StoreController::class);
    Route::get('/stores/{store}/users', [\App\Http\Controllers\SuperAdmin\StoreController::class, 'users'])->name('superadmin.stores.users');
    Route::get('/stores/{store}/users/create', [\App\Http\Controllers\SuperAdmin\StoreController::class, 'createUser'])->name('superadmin.stores.users.create');
    Route::post('/stores/{store}/users', [\App\Http\Controllers\SuperAdmin\StoreController::class, 'storeUser'])->name('superadmin.stores.users.store');
    
    // إدارة المستخدمين
    Route::resource('users', \App\Http\Controllers\SuperAdmin\UserController::class);
    
    // التقارير
    Route::prefix('reports')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\ReportController::class, 'index'])->name('superadmin.reports.index');
        Route::get('/sales', [\App\Http\Controllers\SuperAdmin\ReportController::class, 'sales'])->name('superadmin.reports.sales');
        Route::get('/daily', [\App\Http\Controllers\SuperAdmin\ReportController::class, 'daily'])->name('superadmin.reports.daily');
        Route::get('/inventory', [\App\Http\Controllers\SuperAdmin\ReportController::class, 'inventory'])->name('superadmin.reports.inventory');
        Route::get('/activities', [\App\Http\Controllers\SuperAdmin\ReportController::class, 'activities'])->name('superadmin.reports.activities');
    });
});

// Routes أدمن المتجر
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // إدارة الفئات
    Route::resource('categories', CategoryController::class);
    
    // إدارة المنتجات
    Route::resource('products', ProductController::class);
    Route::get('/products/low-stock', [ProductController::class, 'lowStock'])->name('products.low-stock');
    Route::post('/products/create-category', [ProductController::class, 'createCategory'])->name('products.create-category');
    
    // المرتجعات
    Route::resource('returns', \App\Http\Controllers\Admin\ReturnController::class, ['as' => 'admin']);
    
    // الصيانة
    Route::resource('maintenance', \App\Http\Controllers\Admin\MaintenanceController::class, ['as' => 'admin']);
    
    // التحويلات
    Route::resource('transfers', \App\Http\Controllers\Admin\TransferController::class, ['as' => 'admin']);
    
    // الباقات
    Route::resource('packages', \App\Http\Controllers\Admin\PackageController::class, ['as' => 'admin']);
    
    // الخزينة
    Route::get('/treasury', [\App\Http\Controllers\Admin\TreasuryController::class, 'index'])->name('admin.treasury.index');
    Route::post('/treasury/transaction', [\App\Http\Controllers\Admin\TreasuryController::class, 'addTransaction'])->name('admin.treasury.transaction');
    Route::get('/treasury/daily-closing', [\App\Http\Controllers\Admin\TreasuryController::class, 'dailyClosing'])->name('admin.treasury.daily-closing');
    
    // التقارير
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports');
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('admin.reports.sales');
    Route::get('/reports/products', [ReportController::class, 'products'])->name('admin.reports.products');
    Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('admin.reports.inventory');
    Route::get('/reports/daily', [ReportController::class, 'daily'])->name('admin.reports.daily');
    Route::get('/reports/maintenance', [ReportController::class, 'maintenance'])->name('admin.reports.maintenance');
});

// Routes الكاشير
Route::prefix('cashier')->middleware(['auth', 'role:cashier'])->group(function () {
    Route::get('/dashboard', [CashierDashboardController::class, 'index'])->name('cashier.dashboard');
    
    // إدارة الفواتير
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('cashier.invoices.create');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('cashier.invoices.store');
    Route::get('/products/search', [InvoiceController::class, 'searchProducts'])->name('cashier.products.search');
});

// Middleware aliases already registered in Kernel.php
