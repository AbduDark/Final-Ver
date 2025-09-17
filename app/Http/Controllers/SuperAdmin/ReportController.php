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
                'description' => "فاتورة رقم {$invoice->invoice_number} بقيمة {$invoice->net_amount} ر.س",
                'user' => $invoice->user->name,
                'store' => $invoice->store->name,
                'created_at' => $invoice->created_at
            ]);
        }

        $activities = $activities->sortByDesc('created_at')->take(100);

        return view('superadmin.reports.activities', compact('activities'));
    }
}
