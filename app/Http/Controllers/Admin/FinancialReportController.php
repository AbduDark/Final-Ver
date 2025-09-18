<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\TreasuryTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FinancialReportController extends Controller
{
    public function profitLoss(Request $request)
    {
        $storeId = auth()->user()->store_id;
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        // إجمالي المبيعات
        $totalSales = Invoice::where('store_id', $storeId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('net_amount');

        // تكلفة البضاعة المباعة
        $costOfGoodsSold = 0;
        $invoices = Invoice::with('items.product')
            ->where('store_id', $storeId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        foreach ($invoices as $invoice) {
            foreach ($invoice->items as $item) {
                if ($item->product) {
                    $costOfGoodsSold += $item->quantity * $item->product->purchase_price;
                }
            }
        }

        // إجمالي المصروفات
        $totalExpenses = TreasuryTransaction::whereHas('treasury', function($query) use ($storeId) {
                $query->where('store_id', $storeId);
            })
            ->where('type', 'expense')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $grossProfit = $totalSales - $costOfGoodsSold;
        $netProfit = $grossProfit - $totalExpenses;

        return view('admin.reports.profit-loss', compact(
            'totalSales', 'costOfGoodsSold', 'totalExpenses',
            'grossProfit', 'netProfit', 'startDate', 'endDate'
        ));
    }

    public function cashFlow(Request $request)
    {
        $storeId = auth()->user()->store_id;
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        $cashInflows = TreasuryTransaction::whereHas('treasury', function($query) use ($storeId) {
                $query->where('store_id', $storeId);
            })
            ->where('type', 'income')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $cashOutflows = TreasuryTransaction::whereHas('treasury', function($query) use ($storeId) {
                $query->where('store_id', $storeId);
            })
            ->where('type', 'expense')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.reports.cash-flow', compact(
            'cashInflows', 'cashOutflows', 'startDate', 'endDate'
        ));
    }
}
