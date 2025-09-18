<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Cashier\{CashierDashboardController, InvoiceController};
use App\Http\Controllers\SuperAdmin\{SuperAdminDashboardController, StoreController, UserController, ReportController};
use App\Http\Controllers\Admin\{ProductController, CategoryController, ReportController as AdminReportController, ReturnController, MaintenanceController, TransferController, PackageController, TreasuryController ,AdminDashboardController};
use Illuminate\Support\Facades\Route;

// Routes المصادقة
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Routes السوبر أدمن
Route::prefix('superadmin')->middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('superadmin.dashboard');

    // إدارة المتاجر
    Route::resource('stores', StoreController::class, ['as' => 'superadmin']);
    Route::get('/stores/{store}/users', [StoreController::class, 'users'])->name('superadmin.stores.users');
    Route::get('/stores/{store}/users/create', [StoreController::class, 'createUser'])->name('superadmin.stores.users.create');
    Route::post('/stores/{store}/users', [StoreController::class, 'storeUser'])->name('superadmin.stores.users.store');
    Route::delete('/stores/{store}/users/{user}', [StoreController::class, 'destroyUser'])->name('superadmin.stores.users.destroy');
    Route::put('/stores/{store}/users/{user}', [StoreController::class, 'updateUser'])->name('superadmin.stores.users.update');

    // إدارة المستخدمين
    Route::resource('users', UserController::class, ['as' => 'superadmin']);

    // إدارة الخزينة للسوبر أدمن
    Route::get('/treasury', [App\Http\Controllers\SuperAdmin\TreasuryController::class, 'index'])->name('superadmin.treasury.index');
    Route::get('/treasury/activities', [App\Http\Controllers\SuperAdmin\TreasuryController::class, 'activities'])->name('superadmin.treasury.activities');

    // إدارة الصيانة للسوبر أدمن
    Route::get('/maintenance', [App\Http\Controllers\SuperAdmin\MaintenanceController::class, 'index'])->name('superadmin.maintenance.index');

    // إدارة الباقات للسوبر أدمن
    Route::get('/packages', [App\Http\Controllers\SuperAdmin\PackageController::class, 'index'])->name('superadmin.packages.index');

    // التقارير
    Route::prefix('reports')->group(function () {
        // تقارير السوبر أدمن
        Route::get('/reports', [App\Http\Controllers\SuperAdmin\ReportController::class, 'index'])->name('superadmin.reports.index');
        Route::get('/reports/sales', [App\Http\Controllers\SuperAdmin\ReportController::class, 'sales'])->name('superadmin.reports.sales');
        Route::get('/reports/daily', [App\Http\Controllers\SuperAdmin\ReportController::class, 'daily'])->name('superadmin.reports.daily');
        Route::get('/reports/weekly', [App\Http\Controllers\SuperAdmin\ReportController::class, 'weekly'])->name('superadmin.reports.weekly');
        Route::get('/reports/monthly', [App\Http\Controllers\SuperAdmin\ReportController::class, 'monthly'])->name('superadmin.reports.monthly');
        Route::get('/reports/inventory', [App\Http\Controllers\SuperAdmin\ReportController::class, 'inventory'])->name('superadmin.reports.inventory');
        Route::get('/reports/activities', [App\Http\Controllers\SuperAdmin\ReportController::class, 'activities'])->name('superadmin.reports.activities');
        Route::get('/reports/profit-loss', [App\Http\Controllers\SuperAdmin\ReportController::class, 'profitLoss'])->name('superadmin.reports.profit-loss');
        Route::get('/reports/top-products', [App\Http\Controllers\SuperAdmin\ReportController::class, 'topProducts'])->name('superadmin.reports.top-products');
        Route::get('/reports/customer-analysis', [App\Http\Controllers\SuperAdmin\ReportController::class, 'customerAnalysis'])->name('superadmin.reports.customer-analysis');
        Route::get('/reports/cashier-performance', [App\Http\Controllers\SuperAdmin\ReportController::class, 'cashierPerformance'])->name('superadmin.reports.cashier-performance');
        Route::get('/reports/daily-comparison', [App\Http\Controllers\SuperAdmin\ReportController::class, 'dailyComparison'])->name('superadmin.reports.daily-comparison');
        Route::get('/reports/low-stock-alert', [App\Http\Controllers\SuperAdmin\ReportController::class, 'lowStockAlert'])->name('superadmin.reports.low-stock-alert');
        Route::get('/reports/maintenance', [App\Http\Controllers\SuperAdmin\ReportController::class, 'maintenanceReport'])->name('superadmin.reports.maintenance');
        
        // التقارير المالية المفصلة
        Route::get('/reports/financial/profit-loss', [App\Http\Controllers\SuperAdmin\FinancialReportController::class, 'profitLoss'])->name('superadmin.reports.financial.profit-loss');
        Route::get('/reports/financial/cash-flow', [App\Http\Controllers\SuperAdmin\FinancialReportController::class, 'cashFlow'])->name('superadmin.reports.financial.cash-flow');
        Route::get('/reports/financial/summary', [App\Http\Controllers\SuperAdmin\FinancialReportController::class, 'financialSummary'])->name('superadmin.reports.financial.summary');
    });

    // الإشعارات
    Route::prefix('notifications')->group(function () {
        Route::get('/', [App\Http\Controllers\SuperAdmin\NotificationController::class, 'index'])->name('superadmin.notifications.index');
        Route::post('/{notification}/read', [App\Http\Controllers\SuperAdmin\NotificationController::class, 'markAsRead'])->name('superadmin.notifications.read');
    });
});

// Routes أدمن المتجر
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // إدارة الفئات
    Route::resource('categories', CategoryController::class, ['as' => 'admin']);

    // إدارة المنتجات
    Route::get('/products/low-stock', [ProductController::class, 'lowStock'])->name('admin.products.low-stock');
    Route::post('/products/create-category', [ProductController::class, 'createCategory'])->name('admin.products.create-category');
    Route::resource('products', ProductController::class, ['as' => 'admin']);


    // إدارة الفواتير - الأدمن له نفس صلاحيات الكاشير وأكثر
    Route::get('/invoices', [\App\Http\Controllers\Cashier\InvoiceController::class, 'index'])->name('admin.invoices.index');
    Route::get('/invoices/create', [\App\Http\Controllers\Cashier\InvoiceController::class, 'create'])->name('admin.invoices.create');
    Route::post('/invoices', [\App\Http\Controllers\Cashier\InvoiceController::class, 'store'])->name('admin.invoices.store');
    Route::get('/invoices/{invoice}', [\App\Http\Controllers\Cashier\InvoiceController::class, 'show'])->name('admin.invoices.show');
    Route::get('/invoices/{invoice}/print', [\App\Http\Controllers\Cashier\InvoiceController::class, 'print'])->name('admin.invoices.print');
    Route::get('/invoices/search', [\App\Http\Controllers\Cashier\InvoiceController::class, 'search'])->name('admin.invoices.search');
    Route::get('/products/search', [\App\Http\Controllers\Cashier\InvoiceController::class, 'searchProducts'])->name('admin.products.search');
    Route::get('/invoices/items/{invoice_number}', [\App\Http\Controllers\Admin\ReturnController::class, 'getInvoiceItems'])->name('admin.invoices.items');


    // المرتجعات
    Route::resource('returns', ReturnController::class, ['as' => 'admin']);

    // الصيانة
    Route::resource('maintenance', MaintenanceController::class, ['as' => 'admin']);
    Route::patch('/maintenance/{maintenance}/status', [MaintenanceController::class, 'updateStatus'])->name('admin.maintenance.status');
    Route::get('/maintenance/{maintenance}/edit', [MaintenanceController::class, 'edit'])->name('admin.maintenance.edit');
    Route::delete('/maintenance/{maintenance}', [MaintenanceController::class, 'destroy'])->name('admin.maintenance.destroy');


    // التحويلات
    Route::resource('transfers', TransferController::class, ['as' => 'admin']);
    Route::get('/transfers/create', [TransferController::class, 'create'])->name('admin.transfers.create');


    // الباقات
    Route::resource('packages', PackageController::class, ['as' => 'admin']);

    // الخزينة
    Route::get('/treasury', [TreasuryController::class, 'index'])->name('admin.treasury.index');
    Route::post('/treasury/transaction', [TreasuryController::class, 'addTransaction'])->name('admin.treasury.transaction');
    Route::get('/treasury/daily-closing', [TreasuryController::class, 'dailyClosing'])->name('admin.treasury.daily-closing');
    Route::post('/treasury/close-day', [TreasuryController::class, 'closeDay'])->name('admin.treasury.close-day');

    // التقارير
    Route::prefix('reports')->group(function () {
        Route::get('/', [AdminReportController::class, 'index'])->name('admin.reports.index');
        Route::get('/sales', [AdminReportController::class, 'sales'])->name('admin.reports.sales');
        Route::get('/products', [AdminReportController::class, 'products'])->name('admin.reports.products');
        Route::get('/inventory', [AdminReportController::class, 'inventory'])->name('admin.reports.inventory');
        Route::get('/inventory-value', [AdminReportController::class, 'inventoryValue'])->name('admin.reports.inventory-value');
        Route::get('/daily', [AdminReportController::class, 'daily'])->name('admin.reports.daily');
        Route::get('/maintenance', [AdminReportController::class, 'maintenance'])->name('admin.reports.maintenance');
        Route::get('/daily-closing', [AdminReportController::class, 'dailyClosing'])->name('admin.reports.daily-closing');
    });
});

// Routes الكاشير
Route::prefix('cashier')->middleware(['auth', 'role:cashier'])->group(function () {
    Route::get('/dashboard', [CashierDashboardController::class, 'index'])->name('cashier.dashboard');

    // إدارة الفواتير
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('cashier.invoices.index');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('cashier.invoices.create');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('cashier.invoices.store');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('cashier.invoices.show');
    Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('cashier.invoices.print');
    Route::get('/products/search', [InvoiceController::class, 'searchProducts'])->name('cashier.products.search');

    // البحث في الفواتير
    Route::get('/invoices/search', [InvoiceController::class, 'search'])->name('cashier.invoices.search');

    // إدارة الصيانة للكاشير (إنشاء فقط)
    Route::get('/maintenance', [App\Http\Controllers\Cashier\MaintenanceController::class, 'index'])->name('cashier.maintenance.index');
    Route::get('/maintenance/create', [App\Http\Controllers\Cashier\MaintenanceController::class, 'create'])->name('cashier.maintenance.create');
    Route::post('/maintenance', [App\Http\Controllers\Cashier\MaintenanceController::class, 'store'])->name('cashier.maintenance.store');
});

// Middleware aliases already registered in Kernel.php