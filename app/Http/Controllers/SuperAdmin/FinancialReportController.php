<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\{Invoice, Store, Product, TreasuryTransaction, MaintenanceRequest};
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinancialReportController extends Controller
{
    public function profitLoss(Request $request)
    {
        $superAdmin = auth()->user();
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        // إجمالي الإيرادات من جميع المتاجر
        $totalRevenue = Invoice::whereHas('store', function($query) use ($superAdmin) {
            $query->where('super_admin_id', $superAdmin->id);
        })->whereBetween('created_at', [$startDate, $endDate])
          ->sum('net_amount');

        // تكلفة البضاعة المباعة
        $totalCost = 0;
        $invoices = Invoice::with('items.product')
            ->whereHas('store', function($query) use ($superAdmin) {
                $query->where('super_admin_id', $superAdmin->id);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        foreach ($invoices as $invoice) {
            foreach ($invoice->items as $item) {
                if ($item->product) {
                    $totalCost += $item->quantity * $item->product->purchase_price;
                }
            }
        }

        // إجمالي المصروفات
        $totalExpenses = TreasuryTransaction::whereHas('treasury.store', function($query) use ($superAdmin) {
            $query->where('super_admin_id', $superAdmin->id);
        })->where('type', 'expense')
          ->whereBetween('created_at', [$startDate, $endDate])
          ->sum('amount');

        $grossProfit = $totalRevenue - $totalCost;
        $netProfit = $grossProfit - $totalExpenses;
        $profitMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0;

        // تفاصيل الأرباح حسب المتاجر
        $storesProfits = Store::where('super_admin_id', $superAdmin->id)
            ->with(['invoices' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->get()
            ->map(function($store) use ($startDate, $endDate) {
                $revenue = $store->invoices->sum('net_amount');
                $cost = 0;

                foreach ($store->invoices as $invoice) {
                    foreach ($invoice->items as $item) {
                        if ($item->product) {
                            $cost += $item->quantity * $item->product->purchase_price;
                        }
                    }
                }

                $expenses = TreasuryTransaction::whereHas('treasury', function($query) use ($store) {
                    $query->where('store_id', $store->id);
                })->where('type', 'expense')
                  ->whereBetween('created_at', [$startDate, $endDate])
                  ->sum('amount');

                $profit = $revenue - $cost - $expenses;
                $margin = $revenue > 0 ? ($profit / $revenue) * 100 : 0;

                return [
                    'store' => $store,
                    'revenue' => $revenue,
                    'cost' => $cost,
                    'expenses' => $expenses,
                    'profit' => $profit,
                    'margin' => $margin
                ];
            });

        return view('superadmin.reports.profit-loss', compact(
            'totalRevenue', 'totalCost', 'totalExpenses', 'grossProfit',
            'netProfit', 'profitMargin', 'storesProfits', 'startDate', 'endDate'
        ));
    }

    public function cashFlow(Request $request)
    {
        $superAdmin = auth()->user();
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        $cashInflows = TreasuryTransaction::whereHas('treasury.store', function($query) use ($superAdmin) {
                $query->where('super_admin_id', $superAdmin->id);
            })
            ->where('type', 'income')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['treasury.store', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $cashOutflows = TreasuryTransaction::whereHas('treasury.store', function($query) use ($superAdmin) {
                $query->where('super_admin_id', $superAdmin->id);
            })
            ->where('type', 'expense')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['treasury.store', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalInflows = $cashInflows->sum('amount');
        $totalOutflows = $cashOutflows->sum('amount');
        $netCashFlow = $totalInflows - $totalOutflows;

        return view('superadmin.reports.cash-flow', compact(
            'cashInflows', 'cashOutflows', 'totalInflows',
            'totalOutflows', 'netCashFlow', 'startDate', 'endDate'
        ));
    }

    public function financialSummary(Request $request)
    {
        $superAdmin = auth()->user();
        $period = $request->period ?? 'month';

        switch ($period) {
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                break;
            case 'quarter':
                $startDate = Carbon::now()->startOfQuarter();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                break;
            default:
                $startDate = Carbon::now()->startOfMonth();
        }

        $endDate = Carbon::now();

        // الإيرادات والتكاليف
        $revenue = Invoice::whereHas('store', function($query) use ($superAdmin) {
            $query->where('super_admin_id', $superAdmin->id);
        })->whereBetween('created_at', [$startDate, $endDate])->sum('net_amount');

        $expenses = TreasuryTransaction::whereHas('treasury.store', function($query) use ($superAdmin) {
            $query->where('super_admin_id', $superAdmin->id);
        })->where('type', 'expense')
          ->whereBetween('created_at', [$startDate, $endDate])
          ->sum('amount');

        // تحليل الاتجاهات
        $dailyRevenue = Invoice::whereHas('store', function($query) use ($superAdmin) {
            $query->where('super_admin_id', $superAdmin->id);
        })->whereBetween('created_at', [$startDate, $endDate])
          ->selectRaw('DATE(created_at) as date, SUM(net_amount) as revenue')
          ->groupBy('date')
          ->orderBy('date')
          ->get();

        // أفضل المتاجر أداءً
        $topStores = Store::where('super_admin_id', $superAdmin->id)
            ->withSum(['invoices as revenue' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }], 'net_amount')
            ->orderByDesc('revenue')
            ->take(5)
            ->get();

        return view('superadmin.reports.financial-summary', compact(
            'revenue', 'expenses', 'dailyRevenue', 'topStores',
            'period', 'startDate', 'endDate'
        ));
    }
}
