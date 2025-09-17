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
});

// Routes أدمن المتجر
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // إدارة الفئات
    Route::resource('categories', CategoryController::class);
    
    // إدارة المنتجات
    Route::resource('products', ProductController::class);
    Route::get('/products/low-stock', [ProductController::class, 'lowStock'])->name('products.low-stock');
    
    // التقارير
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports');
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('admin.reports.sales');
    Route::get('/reports/products', [ReportController::class, 'products'])->name('admin.reports.products');
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
