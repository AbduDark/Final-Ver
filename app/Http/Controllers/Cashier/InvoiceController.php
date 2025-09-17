<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Product, Invoice, InvoiceItem, Treasury, TreasuryTransaction};
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        $products = Product::where('store_id', $user->store_id)
            ->with('category')
            ->get();
        
        return view('cashier.invoices.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'customer_name' => 'nullable|string',
            'customer_phone' => 'nullable|string',
            'payment_method' => 'required|in:cash,card,transfer',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $user = auth()->user();
        
        DB::transaction(function() use ($request, $user) {
            // حساب المجاميع
            $totalAmount = 0;
            $invoiceItems = [];

            foreach ($request->items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                
                if ($product->quantity < $item['quantity']) {
                    throw new \Exception("الكمية المطلوبة غير متوفرة للمنتج: {$product->name}");
                }

                $unitPrice = $product->sale_price;
                $totalPrice = $unitPrice * $item['quantity'];
                $totalAmount += $totalPrice;

                $invoiceItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                ];

                // تقليل الكمية من المخزون بطريقة آمنة
                $affected = Product::where('id', $product->id)
                    ->where('quantity', '>=', $item['quantity'])
                    ->decrement('quantity', $item['quantity']);
                    
                if ($affected === 0) {
                    throw new \Exception("فشل في تحديث المخزون للمنتج: {$product->name}. قد يكون قد نفد المخزون.");
                }
            }

            $discount = $request->discount ?? 0;
            $tax = $totalAmount * 0.15; // ضريبة 15%
            $netAmount = $totalAmount - $discount + $tax;

            // إنشاء رقم فاتورة آمن
            $invoiceNumber = $this->generateUniqueInvoiceNumber($user->store_id);
            
            // إنشاء الفاتورة
            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'total_amount' => $totalAmount,
                'discount' => $discount,
                'tax' => $tax,
                'net_amount' => $netAmount,
                'payment_method' => $request->payment_method,
                'store_id' => $user->store_id,
                'user_id' => $user->id,
            ]);

            // إنشاء عناصر الفاتورة
            foreach ($invoiceItems as $item) {
                $invoice->items()->create($item);
            }

            // تحديث الخزينة حسب طريقة الدفع
            $treasury = Treasury::lockForUpdate()->firstOrCreate(['store_id' => $user->store_id]);
            
            // فقط الدفع النقدي يُضاف للخزينة الفعلية
            if ($request->payment_method === 'cash') {
                $treasury->increment('current_balance', $netAmount);
            }
            
            $treasury->update([
                'last_transaction_type' => 'income',
                'last_transaction_amount' => $netAmount,
                'last_transaction_date' => now(),
            ]);

            // تسجيل حركة الخزينة
            TreasuryTransaction::create([
                'treasury_id' => $treasury->id,
                'type' => 'income',
                'amount' => $netAmount,
                'description' => "فاتورة رقم: {$invoice->invoice_number} - {$this->getPaymentMethodName($request->payment_method)}",
                'reference_type' => 'invoice',
                'reference_id' => $invoice->id,
                'user_id' => $user->id,
            ]);
        });

        return redirect()->route('cashier.invoices.create')
            ->with('success', 'تم إنشاء الفاتورة بنجاح');
    }

    public function searchProducts(Request $request)
    {
        $search = $request->get('q');
        $user = auth()->user();
        
        $products = Product::where('store_id', $user->store_id)
            ->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
            })
            ->where('quantity', '>', 0)
            ->with('category')
            ->limit(20)
            ->get();

        return response()->json($products);
    }
    
    private function generateUniqueInvoiceNumber($storeId)
    {
        $date = date('Ymd');
        $attempts = 0;
        
        do {
            $sequence = str_pad(Invoice::where('store_id', $storeId)
                ->whereDate('created_at', today())
                ->count() + 1 + $attempts, 4, '0', STR_PAD_LEFT);
                
            $invoiceNumber = "INV-{$storeId}-{$date}-{$sequence}";
            
            $exists = Invoice::where('invoice_number', $invoiceNumber)->exists();
            $attempts++;
            
        } while ($exists && $attempts < 100);
        
        if ($exists) {
            throw new \Exception('فشل في إنشاء رقم فاتورة فريد');
        }
        
        return $invoiceNumber;
    }
    
    private function getPaymentMethodName($method)
    {
        return match($method) {
            'cash' => 'نقداً',
            'card' => 'بطاقة ائتمانية', 
            'transfer' => 'تحويل',
            default => 'غير محدد'
        };
    }
}
