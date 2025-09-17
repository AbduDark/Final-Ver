
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{ProductReturn, Invoice, Product, Treasury, TreasuryTransaction};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $returns = ProductReturn::where('store_id', $user->store_id)
            ->with(['invoice', 'product', 'user'])
            ->latest()
            ->paginate(20);
        
        return view('admin.returns.index', compact('returns'));
    }

    public function create()
    {
        return view('admin.returns.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|string',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string',
        ]);

        $user = auth()->user();
        
        // البحث عن الفاتورة
        $invoice = Invoice::where('invoice_number', $request->invoice_number)
            ->where('store_id', $user->store_id)
            ->first();
        
        if (!$invoice) {
            return back()->withErrors(['invoice_number' => 'رقم الفاتورة غير صحيح']);
        }
        
        // التحقق من وجود المنتج في الفاتورة
        $invoiceItem = $invoice->items()
            ->where('product_id', $request->product_id)
            ->first();
        
        if (!$invoiceItem || $invoiceItem->quantity < $request->quantity) {
            return back()->withErrors(['quantity' => 'الكمية المطلوبة غير صحيحة']);
        }

        DB::transaction(function() use ($request, $user, $invoice, $invoiceItem) {
            $product = Product::findOrFail($request->product_id);
            
            // حساب قيمة الاسترداد
            $refundAmount = $invoiceItem->unit_price * $request->quantity;
            
            // إنشاء المرتجع
            ProductReturn::create([
                'invoice_id' => $invoice->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'reason' => $request->reason,
                'refund_amount' => $refundAmount,
                'store_id' => $user->store_id,
                'user_id' => $user->id,
            ]);
            
            // إعادة المنتج للمخزون
            $product->increment('quantity', $request->quantity);
            
            // تحديث الخزينة
            $treasury = Treasury::where('store_id', $user->store_id)->first();
            if ($treasury) {
                $treasury->decrement('current_balance', $refundAmount);
                
                // تسجيل حركة الخزينة
                TreasuryTransaction::create([
                    'treasury_id' => $treasury->id,
                    'type' => 'expense',
                    'amount' => $refundAmount,
                    'description' => "مرتجع من فاتورة رقم: {$invoice->invoice_number} - {$product->name}",
                    'reference_type' => 'return',
                    'reference_id' => $invoice->id,
                    'user_id' => $user->id,
                ]);
            }
        });

        return redirect()->route('admin.returns.index')
            ->with('success', 'تم تسجيل المرتجع بنجاح');
    }
}
