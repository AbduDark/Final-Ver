<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Store, Product, Invoice, Category, ProductReturn, MaintenanceRequest};
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $store = $user->store;

        if (!$store) {
            return redirect()->route('login')->with('error', 'لا يوجد متجر مُحدد لهذا المستخدم');
        }

        // إحصائيات المتجر
        $totalProducts = Product::where('store_id', $store->id)->count();
        $lowStockProducts = Product::where('store_id', $store->id)
            ->where('stock', '<=', 'min_stock')->count();
        $totalCategories = Category::where('store_id', $store->id)->count();
        $todayInvoices = Invoice::where('store_id', $store->id)
            ->whereDate('created_at', today())->count();
        $todaySales = Invoice::where('store_id', $store->id)
            ->whereDate('created_at', today())->sum('total');
        $totalReturns = ProductReturn::where('store_id', $store->id)->count();
        $pendingMaintenance = MaintenanceRequest::where('store_id', $store->id)
            ->where('status', 'pending')->count();

        // أحدث الفواتير
        $recentInvoices = Invoice::where('store_id', $store->id)
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        // المنتجات قليلة المخزون
        $lowStockProductsList = Product::where('store_id', $store->id)
            ->where('stock', '<=', 'min_stock')
            ->with('category')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'store',
            'totalProducts',
            'lowStockProducts', 
            'totalCategories',
            'todayInvoices',
            'todaySales',
            'totalReturns',
            'pendingMaintenance',
            'recentInvoices',
            'lowStockProductsList'
        ));
    }
}