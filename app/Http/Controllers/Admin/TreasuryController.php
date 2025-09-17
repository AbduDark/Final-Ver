<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Treasury, TreasuryTransaction};
use Illuminate\Http\Request;

class TreasuryController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $treasury = Treasury::firstOrCreate(['store_id' => $user->store_id]);

        $transactions = TreasuryTransaction::where('treasury_id', $treasury->id)
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('admin.treasury.index', compact('treasury', 'transactions'));
    }

    public function addTransaction(Request $request)
    {
        $request->validate([
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string',
        ]);

        $user = auth()->user();
        $treasury = Treasury::firstOrCreate(['store_id' => $user->store_id]);

        // تحديث رصيد الخزينة
        if ($request->type === 'income') {
            $treasury->increment('current_balance', $request->amount);
        } else {
            $treasury->decrement('current_balance', $request->amount);
        }

        // تسجيل المعاملة
        TreasuryTransaction::create([
            'treasury_id' => $treasury->id,
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'reference_type' => 'manual',
            'reference_id' => 0,
            'user_id' => $user->id,
        ]);

        return redirect()->route('admin.treasury.index')
            ->with('success', 'تم إضافة المعاملة بنجاح');
    }

    public function dailyClosing()
    {
        $user = auth()->user();
        $treasury = Treasury::where('store_id', $user->store_id)->first();

        $todayTransactions = TreasuryTransaction::where('treasury_id', $treasury->id)
            ->whereDate('created_at', today())
            ->get();

        $todayIncome = $todayTransactions->where('type', 'income')->sum('amount');
        $todayExpense = $todayTransactions->where('type', 'expense')->sum('amount');

        return view('admin.treasury.daily-closing', compact(
            'treasury', 'todayTransactions', 'todayIncome', 'todayExpense'
        ));
    }
}
