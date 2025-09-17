<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Invoice, Product, Treasury, TreasuryTransaction, ProductReturn, MaintenanceRequest};
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function sales(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now());

        $salesData = Invoice::where('store_id', $user->store_id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['user', 'items.product'])
            ->get();

        $totalSales = $salesData->sum('net_amount');
        $totalInvoices = $salesData->count();

        return view('admin.reports.sales', compact(
            'salesData', 'totalSales', 'totalInvoices', 'startDate', 'endDate'
        ));
    }

    public function daily(Request $request)
    {
        $user = auth()->user();
        $date = $request->get('date', Carbon::today());

        $dailyData = Invoice::where('store_id', $user->store_id)
            ->whereDate('created_at', $date)
            ->with(['user', 'items.product'])
            ->get();

        $totalRevenue = $dailyData->sum('net_amount');
        $totalInvoices = $dailyData->count();

        return view('admin.reports.daily', compact(
            'dailyData', 'totalRevenue', 'totalInvoices', 'date'
        ));
    }

    public function inventory()
    {
        $user = auth()->user();
        
        $products = Product::where('store_id', $user->store_id)
            ->with('category')
            ->get();

        $totalProducts = $products->count();
        $lowStockProducts = $products->where('quantity', '<=', 'min_quantity')->count();
        $totalValue = $products->sum(function($product) {
            return $product->quantity * $product->purchase_price;
        });

        return view('admin.reports.inventory', compact(
            'products', 'totalProducts', 'lowStockProducts', 'totalValue'
        ));
    }

    public function inventoryValue()
    {
        $user = auth()->user();
        
        $products = Product::where('store_id', $user->store_id)
            ->with('category')
            ->get();

        $inventoryValue = $products->map(function($product) {
            return [
                'product' => $product,
                'purchase_value' => $product->quantity * $product->purchase_price,
                'sale_value' => $product->quantity * $product->sale_price,
            ];
        });

        $totalPurchaseValue = $inventoryValue->sum('purchase_value');
        $totalSaleValue = $inventoryValue->sum('sale_value');

        return view('admin.reports.inventory-value', compact(
            'inventoryValue', 'totalPurchaseValue', 'totalSaleValue'
        ));
    }

    public function maintenance()
    {
        $user = auth()->user();
        
        $maintenanceRequests = MaintenanceRequest::where('store_id', $user->store_id)
            ->with('technician')
            ->latest()
            ->get();

        $totalRequests = $maintenanceRequests->count();
        $completedRequests = $maintenanceRequests->where('status', 'completed')->count();
        $pendingRequests = $maintenanceRequests->where('status', 'pending')->count();

        return view('admin.reports.maintenance', compact(
            'maintenanceRequests', 'totalRequests', 'completedRequests', 'pendingRequests'
        ));
    }

    public function dailyClosing()
    {
        $user = auth()->user();
        $treasury = Treasury::where('store_id', $user->store_id)->first();

        $todayTransactions = TreasuryTransaction::where('treasury_id', $treasury->id)
            ->whereDate('created_at', today())
            ->with('user')
            ->get();

        $todayIncome = $todayTransactions->where('type', 'income')->sum('amount');
        $todayExpense = $todayTransactions->where('type', 'expense')->sum('amount');

        return view('admin.reports.daily-closing', compact(
            'treasury', 'todayTransactions', 'todayIncome', 'todayExpense'
        ));
    }

    public function products()
    {
        $user = auth()->user();
        
        $products = Product::where('store_id', $user->store_id)
            ->with(['category'])
            ->withCount(['invoiceItems'])
            ->get();

        return view('admin.reports.products', compact('products'));
    }
}
