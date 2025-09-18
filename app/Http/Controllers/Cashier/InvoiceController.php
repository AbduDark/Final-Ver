<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Product, Invoice, InvoiceItem, Treasury, TreasuryTransaction};
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Invoice::where('store_id', $user->store_id)
            ->with(['user', 'items.product']);

        // إذا كان كاشير، يرى فقط فواتيره
        // إذا كان أدمن، يرى كل فواتير المتجر
        if ($user->type === 'cashier') {
            $query->where('user_id', $user->id);
        }

        $invoices = $query->latest()->paginate(20);

        // تحديد الـ view حسب نوع المستخدم
        $viewPath = $user->type === 'admin' ? 'admin.invoices.index' : 'cashier.invoices.index';

        return view($viewPath, compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $user = auth()->user();

        // التأكد من أن الفاتورة تخص نفس المتجر
        if ($invoice->store_id !== $user->store_id) {
            abort(403, 'غير مسموح بالوصول إلى هذه الفاتورة');
        }

        // إذا كان كاشير، يجب أن تكون الفاتورة له
        // إذا كان أدمن، يمكنه رؤية كل الفواتير في متجره
        if ($user->type === 'cashier' && $invoice->user_id !== $user->id) {
            abort(403, 'غير مسموح بالوصول إلى هذه الفاتورة');
        }

        $invoice->load(['items.product', 'user']);

        // تحديد الـ view حسب نوع المستخدم
        $viewPath = $user->type === 'admin' ? 'admin.invoices.show' : 'cashier.invoices.show';

        return view($viewPath, compact('invoice'));
    }

    public function print(Invoice $invoice)
    {
        $user = auth()->user();

        // التأكد من أن الفاتورة تخص نفس المتجر
        if ($invoice->store_id !== $user->store_id) {
            abort(403, 'غير مسموح بالوصول إلى هذه الفاتورة');
        }

        // إذا كان كاشير، يجب أن تكون الفاتورة له
        // إذا كان أدمن، يمكنه طباعة كل الفواتير في متجره
        if ($user->type === 'cashier' && $invoice->user_id !== $user->id) {
            abort(403, 'غير مسموح بالوصول إلى هذه الفاتورة');
        }

        $invoice->load(['items.product', 'user', 'store']);

        // تحديد الـ view حسب نوع المستخدم
        $viewPath = $user->type === 'admin' ? 'admin.invoices.print' : 'cashier.invoices.print';

        return view($viewPath, compact('invoice'));
    }

    public function search(Request $request)
    {
        $user = auth()->user();
        $search = $request->get('q');

        $query = Invoice::where('store_id', $user->store_id)
            ->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            })
            ->with(['user', 'items.product']);

        // إذا كان كاشير، يرى فقط فواتيره
        if ($user->type === 'cashier') {
            $query->where('user_id', $user->id);
        }

        $invoices = $query->latest()->paginate(20);

        // تحديد الـ view حسب نوع المستخدم
        $viewPath = $user->type === 'admin' ? 'admin.invoices.index' : 'cashier.invoices.index';

        return view($viewPath, compact('invoices'));
    }
    public function create()
    {
        $user = auth()->user();
        $products = Product::where('store_id', $user->store_id)
            ->with('category')
            ->get();

        // تحديد الـ view حسب نوع المستخدم
        $viewPath = $user->type === 'admin' ? 'admin.invoices.create' : 'cashier.invoices.create';

        return view($viewPath, compact('products'));
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

        DB::transaction(function() use ($request, &$invoice) {
            $user = auth()->user();

            $invoice = Invoice::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'user_id' => $user->id,
                'store_id' => $user->store_id,
                'subtotal' => $request->subtotal,
                'discount_percentage' => $request->discount_percentage ?? 0,
                'discount_amount' => $request->discount_amount ?? 0,
                'tax_amount' => $request->tax_amount ?? 0,
                'total' => $request->total,
                'net_amount' => $request->net_amount,
                'status' => 'paid'
            ]);

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price']
                ]);

                $product->decrement('quantity', $item['quantity']);
            }

            // تسجيل المعاملة في الخزينة
            $treasury = Treasury::firstOrCreate(
                ['store_id' => $user->store_id],
                ['current_balance' => 0]
            );

            $treasury->increment('current_balance', $request->net_amount);
            $treasury->update([
                'last_transaction_type' => 'income',
                'last_transaction_amount' => $request->net_amount,
                'last_transaction_date' => now()
            ]);

            // إنشاء سجل معاملة
            TreasuryTransaction::create([
                'treasury_id' => $treasury->id,
                'user_id' => $user->id,
                'type' => 'income',
                'amount' => $request->net_amount,
                'description' => 'بيع - فاتورة رقم: ' . $invoice->invoice_number,
                'invoice_id' => $invoice->id
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