<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\{Store, User, Invoice, Product, MaintenanceRequest};
use Carbon\Carbon;
use Illuminate\Http\Request;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // إحصائيات عامة لجميع المتاجر
        $totalStores = Store::where('super_admin_id', $user->id)->count();
        $totalUsers = User::whereHas('store', function($query) use ($user) {
            $query->where('super_admin_id', $user->id);
        })->count();

        $todayRevenue = Invoice::whereHas('store', function($query) use ($user) {
            $query->where('super_admin_id', $user->id);
        })->whereDate('created_at', Carbon::today())->sum('net_amount');

        $monthlyRevenue = Invoice::whereHas('store', function($query) use ($user) {
            $query->where('super_admin_id', $user->id);
        })->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()])->sum('net_amount');

        $pendingMaintenance = MaintenanceRequest::whereHas('store', function($query) use ($user) {
            $query->where('super_admin_id', $user->id);
        })->where('status', 'pending')->count();

        // آخر الأنشطة
        $recentActivities = Invoice::whereHas('store', function($query) use ($user) {
            $query->where('super_admin_id', $user->id);
        })->with(['store', 'user'])->latest()->limit(10)->get();

        // إحصائيات المتاجر
        $storesStats = Store::where('super_admin_id', $user->id)
            ->withCount(['users', 'products', 'invoices'])
            ->get();

        // رسم بياني للمبيعات الأسبوعية
        $weeklyChart = Invoice::whereHas('store', function($query) use ($user) {
            $query->where('super_admin_id', $user->id);
        })->whereBetween('created_at', [Carbon::now()->subDays(6), Carbon::now()])
            ->selectRaw('DATE(created_at) as date, SUM(net_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('superadmin.dashboard', compact(
            'totalStores', 'totalUsers', 'todayRevenue', 'monthlyRevenue',
            'pendingMaintenance', 'recentActivities', 'storesStats', 'weeklyChart'
        ));
    }
}
