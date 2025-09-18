<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\{Product, Invoice, Transfer};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
USE App\Models\Notification;

class CashierDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $store = $user->store;
        $today = Carbon::today();

        // استعلام محسن للإحصائيات اليومية
        $todayStats = Invoice::select([
                DB::raw('COUNT(*) as invoice_count'),
                DB::raw('SUM(net_amount) as revenue')
            ])
            ->where('store_id', $store->id)
            ->where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->first();

        $todayInvoices = $todayStats->invoice_count ?? 0;
        $todayRevenue = $todayStats->revenue ?? 0;

        // استعلام محسن للتحويلات المعلقة
        $pendingTransfers = Transfer::where('store_id', $store->id)
            ->where('status', 'pending')
            ->count();

        // آخر الفواتير مع التحسين
        $recentInvoices = Invoice::select(['id', 'invoice_number', 'customer_name', 'net_amount', 'created_at'])
            ->where('store_id', $store->id)
            ->where('user_id', $user->id)
            ->latest()
            ->limit(10)
            ->get();

        // عدد المنتجات منخفضة المخزون
        $lowStockProducts = Product::where('store_id', $store->id)
            ->whereColumn('quantity', '<=', 'min_quantity')
            ->count();

        // الإشعارات الخاصة بالكاشير
        $notifications = Notification::where('store_id', $store->id)
            ->where(function($query) use ($user) {
                $query->where('user_id', $user->id)->orWhereNull('user_id');
            })
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('cashier.dashboard', compact(
            'store', 'todayInvoices', 'todayRevenue',
            'pendingTransfers', 'recentInvoices', 'lowStockProducts', 'notifications'
        ));
    }
}
