<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Transfer, Treasury, TreasuryTransaction};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $transfers = Transfer::where('store_id', $user->store_id)
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('admin.transfers.index', compact('transfers'));
    }

    public function create()
    {
        return view('admin.transfers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'transfer_type' => 'required|in:instant_payment,recharge_cards,vodafone_cash,etisalat_cash,orange_cash',
            'amount' => 'required|numeric|min:0.01',
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
        ]);

        $user = auth()->user();

        DB::transaction(function() use ($request, $user) {
            $transfer = Transfer::create([
                'transfer_number' => $this->generateTransferNumber(),
                'transfer_type' => $request->transfer_type,
                'amount' => $request->amount,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'status' => 'completed',
                'store_id' => $user->store_id,
                'user_id' => $user->id,
            ]);

            // تحديث الخزينة
            $treasury = Treasury::firstOrCreate(['store_id' => $user->store_id]);
            $treasury->increment('current_balance', $request->amount);

            // تسجيل حركة الخزينة
            TreasuryTransaction::create([
                'treasury_id' => $treasury->id,
                'type' => 'income',
                'amount' => $request->amount,
                'description' => "تحويل {$this->getTransferTypeName($request->transfer_type)} - {$request->customer_name}",
                'reference_type' => 'transfer',
                'reference_id' => $transfer->id,
                'user_id' => $user->id,
            ]);
        });

        return redirect()->route('admin.transfers.index')
            ->with('success', 'تم تسجيل التحويل بنجاح');
    }

    private function generateTransferNumber()
    {
        $date = date('Ymd');
        $sequence = str_pad(Transfer::count() + 1, 4, '0', STR_PAD_LEFT);
        return "TRANS-{$date}-{$sequence}";
    }

    private function getTransferTypeName($type)
    {
        return match($type) {
            'instant_payment' => 'دفع فوري',
            'recharge_cards' => 'كروت شحن',
            'vodafone_cash' => 'فودافون كاش',
            'etisalat_cash' => 'اتصالات كاش',
            'orange_cash' => 'أورانج كاش',
            default => 'غير محدد'
        };
    }
}
