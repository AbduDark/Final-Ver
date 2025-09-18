<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\{Store, Invoice, Product, User};
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('superadmin.reports.index');
    }

    public function sales(Request $request)
    {
        $superAdmin = auth()->user();
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now());

        $salesData = Invoice::whereHas('store', function($query) use ($superAdmin) {
            $query->where('super_admin_id', $superAdmin->id);
        })->whereBetween('created_at', [$startDate, $endDate])
            ->with(['store', 'user'])
            ->get();

        $totalSales = $salesData->sum('net_amount');
        $storesSales = $salesData->groupBy('store_id')->map(function($items) {
            return [
                'store' => $items->first()->store,
                'total' => $items->sum('net_amount'),
                'count' => $items->count()
            ];
        });

        return view('superadmin.reports.sales', compact(
            'salesData', 'totalSales', 'storesSales', 'startDate', 'endDate'
        ));
    }

    public function daily(Request $request)
    {
        $superAdmin = auth()->user();
        $date = $request->get('date', Carbon::today());

        $dailyData = Invoice::whereHas('store', function($query) use ($superAdmin) {
            $query->where('super_admin_id', $superAdmin->id);
        })->whereDate('created_at', $date)
            ->with(['store', 'user', 'items.product'])
            ->get();

        return view('superadmin.reports.daily', compact('dailyData', 'date'));
    }

    public function inventory()
    {
        $superAdmin = auth()->user();

        $products = Product::whereHas('store', function($query) use ($superAdmin) {
            $query->where('super_admin_id', $superAdmin->id);
        })->with(['store', 'category'])->get();

        $totalValue = $products->sum(function($product) {
            return $product->quantity * $product->purchase_price;
        });

        $lowStockProducts = $products->filter(function($product) {
            return $product->quantity <= $product->min_quantity;
        });

        return view('superadmin.reports.inventory', compact(
            'products', 'totalValue', 'lowStockProducts'
        ));
    }

    public function activities()
    {
        $superAdmin = auth()->user();

        $activities = collect();

        // إضافة أنشطة المبيعات
        $invoices = Invoice::whereHas('store', function($query) use ($superAdmin) {
            $query->where('super_admin_id', $superAdmin->id);
        })->with(['store', 'user'])->latest()->limit(50)->get();

        foreach($invoices as $invoice) {
            $activities->push([
                'type' => 'sale',
                'description' => "فاتورة رقم {$invoice->invoice_number} بقيمة {$invoice->net_amount} ج.م",
                'user' => $invoice->user->name,
                'store' => $invoice->store->name,
                'created_at' => $invoice->created_at
            ]);
        }

        $activities = $activities->sortByDesc('created_at')->take(100);

        return view('superadmin.reports.activities', compact('activities'));
    }

    public function profitLoss(Request $request)
    {
        $superAdmin = auth()->user();
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now());

        // حساب الإيرادات
        $revenues = Invoice::whereHas('store', function($query) use ($superAdmin) {
            $query->where('super_admin_id', $superAdmin->id);
        })->whereBetween('created_at', [$startDate, $endDate])
            ->with(['store', 'items.product'])
            ->get();

        $totalRevenue = $revenues->sum('net_amount');
        
        // حساب التكلفة الفعلية للمنتجات المباعة
        $totalCost = 0;
        foreach($revenues as $invoice) {
            foreach($invoice->items as $item) {
                $totalCost += ($item->product->purchase_price * $item->quantity);
            }
        }

        $grossProfit = $totalRevenue - $totalCost;
        $profitMargin = $totalRevenue > 0 ? ($grossProfit / $totalRevenue) * 100 : 0;

        // تفاصيل حسب المتاجر
        $storesProfits = $revenues->groupBy('store_id')->map(function($storeInvoices) {
            $storeRevenue = $storeInvoices->sum('net_amount');
            $storeCost = 0;
            
            foreach($storeInvoices as $invoice) {
                foreach($invoice->items as $item) {
                    $storeCost += ($item->product->purchase_price * $item->quantity);
                }
            }
            
            return [
                'store' => $storeInvoices->first()->store,
                'revenue' => $storeRevenue,
                'cost' => $storeCost,
                'profit' => $storeRevenue - $storeCost,
                'margin' => $storeRevenue > 0 ? (($storeRevenue - $storeCost) / $storeRevenue) * 100 : 0
            ];
        });

        return view('superadmin.reports.profit-loss', compact(
            'totalRevenue', 'totalCost', 'grossProfit', 'profitMargin', 
            'storesProfits', 'startDate', 'endDate'
        ));
    }

    public function topProducts(Request $request)
    {
        $superAdmin = auth()->user();
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now());
        $limit = $request->get('limit', 20);

        $topProducts = InvoiceItem::whereHas('invoice', function($query) use ($superAdmin, $startDate, $endDate) {
            $query->whereHas('store', function($storeQuery) use ($superAdmin) {
                $storeQuery->where('super_admin_id', $superAdmin->id);
            })->whereBetween('created_at', [$startDate, $endDate]);
        })->with(['product.store', 'invoice'])
            ->selectRaw('product_id, SUM(quantity) as total_quantity, SUM(total_price) as total_sales, COUNT(*) as times_sold')
            ->groupBy('product_id')
            ->orderByDesc('total_sales')
            ->limit($limit)
            ->get();

        return view('superadmin.reports.top-products', compact('topProducts', 'startDate', 'endDate', 'limit'));
    }

    public function customerAnalysis(Request $request)
    {
        $superAdmin = auth()->user();
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now());

        $customerData = Invoice::whereHas('store', function($query) use ($superAdmin) {
            $query->where('super_admin_id', $superAdmin->id);
        })->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('customer_name')
            ->where('customer_name', '!=', '')
            ->selectRaw('customer_name, customer_phone, COUNT(*) as visit_count, SUM(net_amount) as total_spent, AVG(net_amount) as average_order')
            ->groupBy('customer_name', 'customer_phone')
            ->orderByDesc('total_spent')
            ->get();

        $totalCustomers = $customerData->count();
        $totalSpent = $customerData->sum('total_spent');
        $averageOrderValue = $customerData->avg('average_order');

        return view('superadmin.reports.customer-analysis', compact(
            'customerData', 'totalCustomers', 'totalSpent', 'averageOrderValue', 'startDate', 'endDate'
        ));
    }

    public function cashierPerformance(Request $request)
    {
        $superAdmin = auth()->user();
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now());

        $cashierStats = Invoice::whereHas('store', function($query) use ($superAdmin) {
            $query->where('super_admin_id', $superAdmin->id);
        })->whereBetween('created_at', [$startDate, $endDate])
            ->with(['user.store'])
            ->selectRaw('user_id, COUNT(*) as total_invoices, SUM(net_amount) as total_sales, AVG(net_amount) as average_sale')
            ->groupBy('user_id')
            ->orderByDesc('total_sales')
            ->get();

        return view('superadmin.reports.cashier-performance', compact('cashierStats', 'startDate', 'endDate'));
    }

    public function dailyComparison(Request $request)
    {
        $superAdmin = auth()->user();
        $days = $request->get('days', 30);
        
        $dailyData = Invoice::whereHas('store', function($query) use ($superAdmin) {
            $query->where('super_admin_id', $superAdmin->id);
        })->whereBetween('created_at', [Carbon::now()->subDays($days), Carbon::now()])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as invoice_count, SUM(net_amount) as daily_sales')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $averageDailySales = $dailyData->avg('daily_sales');
        $bestDay = $dailyData->sortByDesc('daily_sales')->first();
        $worstDay = $dailyData->sortBy('daily_sales')->first();

        return view('superadmin.reports.daily-comparison', compact(
            'dailyData', 'averageDailySales', 'bestDay', 'worstDay', 'days'
        ));
    }

    public function lowStockAlert()
    {
        $superAdmin = auth()->user();
        
        $lowStockProducts = Product::whereHas('store', function($query) use ($superAdmin) {
            $query->where('super_admin_id', $superAdmin->id);
        })->whereColumn('quantity', '<=', 'min_quantity')
            ->with(['store', 'category'])
            ->orderBy('quantity')
            ->get();

        $criticalProducts = $lowStockProducts->where('quantity', 0);
        $warningProducts = $lowStockProducts->where('quantity', '>', 0);

        return view('superadmin.reports.low-stock-alert', compact(
            'lowStockProducts', 'criticalProducts', 'warningProducts'
        ));
    }

    public function weekly(Request $request)
    {
        $superAdmin = auth()->user();
        $startOfWeek = $request->get('start_date', Carbon::now()->startOfWeek());
        $endOfWeek = $request->get('end_date', Carbon::now()->endOfWeek());

        $weeklyData = Invoice::whereHas('store', function($query) use ($superAdmin) {
            $query->where('super_admin_id', $superAdmin->id);
        })->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->with(['store', 'user'])
            ->get();

        $totalWeeklyRevenue = $weeklyData->sum('net_amount');
        $dailyBreakdown = $weeklyData->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('Y-m-d');
        })->map(function($items) {
            return [
                'total' => $items->sum('net_amount'),
                'count' => $items->count()
            ];
        });

        return view('superadmin.reports.weekly', compact(
            'weeklyData', 'totalWeeklyRevenue', 'dailyBreakdown', 'startOfWeek', 'endOfWeek'
        ));
    }

    public function monthly(Request $request)
    {
        $superAdmin = auth()->user();
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();

        $monthlyData = Invoice::whereHas('store', function($query) use ($superAdmin) {
            $query->where('super_admin_id', $superAdmin->id);
        })->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->with(['store', 'user'])
            ->get();

        $totalMonthlyRevenue = $monthlyData->sum('net_amount');
        $storesMonthlyStats = $monthlyData->groupBy('store_id')->map(function($items) {
            return [
                'store' => $items->first()->store,
                'total' => $items->sum('net_amount'),
                'count' => $items->count()
            ];
        });

        $weeklyBreakdown = $monthlyData->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('W');
        })->map(function($items) {
            return [
                'total' => $items->sum('net_amount'),
                'count' => $items->count()
            ];
        });

        return view('superadmin.reports.monthly', compact(
            'monthlyData', 'totalMonthlyRevenue', 'storesMonthlyStats', 
            'weeklyBreakdown', 'month'
        ));
    }
}
