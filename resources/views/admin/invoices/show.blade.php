
@extends('layouts.app')

@section('title', 'تفاصيل الفاتورة')
@section('page-title', 'تفاصيل الفاتورة رقم: ' . $invoice->invoice_number)

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.invoices.index') }}" class="nav-link">
            <i class="fas fa-receipt me-2"></i>
            إدارة الفواتير
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>تفاصيل الفاتورة رقم: {{ $invoice->invoice_number }}</h2>
    <div>
        <a href="{{ route('admin.invoices.print', $invoice) }}" class="btn btn-secondary" target="_blank">
            <i class="fas fa-print me-2"></i>طباعة
        </a>
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>العودة للقائمة
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>معلومات الفاتورة</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>رقم الفاتورة:</strong></td>
                        <td>{{ $invoice->invoice_number }}</td>
                    </tr>
                    <tr>
                        <td><strong>التاريخ:</strong></td>
                        <td>{{ $invoice->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>الكاشير:</strong></td>
                        <td>{{ $invoice->user->name ?? 'غير محدد' }}</td>
                    </tr>
                    <tr>
                        <td><strong>اسم العميل:</strong></td>
                        <td>{{ $invoice->customer_name ?: 'عميل عادي' }}</td>
                    </tr>
                    <tr>
                        <td><strong>رقم الهاتف:</strong></td>
                        <td>{{ $invoice->customer_phone ?: 'غير محدد' }}</td>
                    </tr>
                    <tr>
                        <td><strong>طريقة الدفع:</strong></td>
                        <td>
                            @switch($invoice->payment_method)
                                @case('cash')
                                    <span class="badge bg-success">نقداً</span>
                                    @break
                                @case('card')
                                    <span class="badge bg-info">بطاقة ائتمانية</span>
                                    @break
                                @case('transfer')
                                    <span class="badge bg-warning">تحويل</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">غير محدد</span>
                            @endswitch
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>ملخص الفاتورة</h5>
            </div>
            <div class="card-body">
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
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5>عناصر الفاتورة</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>الفئة</th>
                        <th>الكود</th>
                        <th>سعر الوحدة</th>
                        <th>الكمية</th>
                        <th>المجموع</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'منتج محذوف' }}</td>
                        <td>{{ $item->product->category->name ?? 'غير محدد' }}</td>
                        <td>{{ $item->product->code ?? 'غير محدد' }}</td>
                        <td>{{ number_format($item->unit_price, 2) }} ج.م</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->total_price, 2) }} ج.م</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-active">
                        <td colspan="5"><strong>المجموع:</strong></td>
                        <td><strong>{{ number_format($invoice->items->sum('total_price'), 2) }} ج.م</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
