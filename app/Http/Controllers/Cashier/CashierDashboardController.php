<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\{Product, Invoice, Transfer};
use Carbon\Carbon;

class CashierDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $store = $user->store;

        // إحصائيات سريعة
        $todayInvoices = Invoice::where('store_id', $store->id)
            ->where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->count();

        $todayRevenue = Invoice::where('store_id', $store->id)
            ->where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->sum('net_amount');

        $pendingTransfers = Transfer::where('store_id', $store->id)
            ->where('status', 'pending')
            ->count();

        // آخر الفواتير لهذا الكاشير
        $recentInvoices = Invoice::where('store_id', $store->id)
            ->where('user_id', $user->id)
            ->with('items.product')
            ->latest()
            ->limit(10)
            ->get();

        return view('cashier.dashboard', compact(
            'store', 'todayInvoices', 'todayRevenue', 
            'pendingTransfers', 'recentInvoices'
        ));
    }
}
