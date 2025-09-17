
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>طباعة فاتورة - {{ $invoice->invoice_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { font-size: 12px; }
        }
        body { font-family: 'Arial', sans-serif; }
        .invoice-header { border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .invoice-info { background-color: #f8f9fa; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row no-print mb-3">
            <div class="col-12">
                <button onclick="window.print()" class="btn btn-primary">طباعة</button>
                <button onclick="window.close()" class="btn btn-secondary">إغلاق</button>
            </div>
        </div>
        
        <div class="invoice-header text-center">
            <h2>{{ $invoice->store->name ?? 'اسم المتجر' }}</h2>
            <p>{{ $invoice->store->address ?? 'عنوان المتجر' }}</p>
            <p>هاتف: {{ $invoice->store->phone ?? 'رقم الهاتف' }}</p>
            <hr>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="invoice-info">
                    <strong>رقم الفاتورة:</strong> {{ $invoice->invoice_number }}<br>
                    <strong>التاريخ:</strong> {{ $invoice->created_at->format('Y-m-d H:i') }}<br>
                    <strong>الكاشير:</strong> {{ $invoice->user->name }}
                </div>
            </div>
        </div>
        
        <div class="table-responsive mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>الكمية</th>
                        <th>السعر</th>
                        <th>الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->unit_price, 2) }} ج.م</td>
                        <td>{{ number_format($item->total_price, 2) }} ج.م</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">الإجمالي:</th>
                        <th>{{ number_format($invoice->net_amount, 2) }} ج.م</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="text-center mt-4">
            <p>شكراً لتعاملكم معنا</p>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="invoice-info">
                    <h5>معلومات الفاتورة</h5>
                    <p><strong>رقم الفاتورة:</strong> {{ $invoice->invoice_number }}</p>
                    <p><strong>التاريخ:</strong> {{ $invoice->created_at->format('Y-m-d H:i') }}</p>
                    <p><strong>الكاشير:</strong> {{ $invoice->user->name ?? 'غير محدد' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="invoice-info">
                    <h5>معلومات العميل</h5>
                    <p><strong>الاسم:</strong> {{ $invoice->customer_name ?: 'عميل عادي' }}</p>
                    <p><strong>الهاتف:</strong> {{ $invoice->customer_phone ?: 'غير محدد' }}</p>
                    <p><strong>طريقة الدفع:</strong> 
                        @switch($invoice->payment_method)
                            @case('cash') نقداً @break
                            @case('card') بطاقة ائتمانية @break
                            @case('transfer') تحويل @break
                            @default غير محدد
                        @endswitch
                    </p>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>المنتج</th>
                        <th>سعر الوحدة</th>
                        <th>الكمية</th>
                        <th>المجموع</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->product->name ?? 'منتج محذوف' }}</td>
                        <td>{{ number_format($item->unit_price, 2) }} ج.م</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->total_price, 2) }} ج.م</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="row">
            <div class="col-md-6 offset-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td>المجموع الفرعي:</td>
                        <td class="text-end">{{ number_format($invoice->total_amount, 2) }} ج.م</td>
                    </tr>
                    <tr>
                        <td>الخصم:</td>
                        <td class="text-end">{{ number_format($invoice->discount, 2) }} ج.م</td>
                    </tr>
                    <tr>
                        <td>الضريبة (15%):</td>
                        <td class="text-end">{{ number_format($invoice->tax, 2) }} ج.م</td>
                    </tr>
                    <tr class="table-active">
                        <td><strong>المجموع الكلي:</strong></td>
                        <td class="text-end"><strong>{{ number_format($invoice->net_amount, 2) }} ج.م</strong></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <p>شكراً لتعاملكم معنا</p>
            <p>تم إنشاء هذه الفاتورة بواسطة: {{ $invoice->user->name ?? 'النظام' }}</p>
        </div>
    </div>
    
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
