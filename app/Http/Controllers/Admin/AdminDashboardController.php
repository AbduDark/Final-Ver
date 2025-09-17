<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Product, Invoice, MaintenanceRequest, Transfer, Treasury};
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $store = $user->store;

        // إحصائيات سريعة
        $totalProducts = Product::where('store_id', $store->id)->count();

        $todayRevenue = Invoice::where('store_id', $store->id)
            ->whereDate('created_at', Carbon::today())
            ->sum('net_amount');

        $monthlyRevenue = Invoice::where('store_id', $store->id)
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()])
            ->sum('net_amount');

        $pendingMaintenance = MaintenanceRequest::where('store_id', $store->id)
            ->where('status', 'pending')
            ->count();

        $lowStockProducts = Product::where('store_id', $store->id)
            ->whereColumn('quantity', '<=', 'min_quantity')
            ->count();

        $todayInvoices = Invoice::where('store_id', $store->id)
            ->whereDate('created_at', Carbon::today())
            ->count();

        // آخر الفواتير
        $recentInvoices = Invoice::where('store_id', $store->id)
            ->with(['user', 'items.product'])
            ->latest()
            ->limit(10)
            ->get();

        // رسم بياني للمبيعات الأسبوعية
        $weeklyChart = Invoice::where('store_id', $store->id)
            ->whereBetween('created_at', [Carbon::now()->subDays(6), Carbon::now()])
            ->selectRaw('DATE(created_at) as date, SUM(net_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // حالة الخزينة
        $treasury = Treasury::where('store_id', $store->id)->first();
        $currentBalance = $treasury ? $treasury->current_balance : 0;

        return view('admin.dashboard', compact(
            'totalProducts', 'todayRevenue', 'monthlyRevenue', 'pendingMaintenance',
            'lowStockProducts', 'todayInvoices', 'recentInvoices', 'weeklyChart', 'currentBalance'
        ));
    }
}
