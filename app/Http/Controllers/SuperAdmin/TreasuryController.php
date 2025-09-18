<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\{Treasury, TreasuryTransaction, Store};
use Illuminate\Http\Request;

class TreasuryController extends Controller
{
    public function index()
    {
        $superAdmin = auth()->user();

        $treasuries = Treasury::whereHas('store', function($query) use ($superAdmin) {
            $query->where('super_admin_id', $superAdmin->id);
        })->with('store')->get();

        $totalBalance = $treasuries->sum('current_balance');

        return view('superadmin.treasury.index', compact('treasuries', 'totalBalance'));
    }

    public function activities()
    {
        $superAdmin = auth()->user();

        $transactions = TreasuryTransaction::whereHas('treasury.store', function($query) use ($superAdmin) {
            $query->where('super_admin_id', $superAdmin->id);
        })->with(['treasury.store', 'user'])->latest()->paginate(50);

        return view('superadmin.treasury.activities', compact('transactions'));
    }
}
