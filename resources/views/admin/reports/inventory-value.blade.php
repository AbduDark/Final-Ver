
@extends('layouts.app')

@section('title', 'تقرير قيمة المخزون')
@section('page-title', 'تقرير قيمة المخزون')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.reports.index') }}" class="nav-link active">
            <i class="fas fa-chart-bar me-2"></i>
            التقارير
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>تقرير قيمة المخزون</h2>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h4>{{ number_format($totalPurchaseValue ?? 0, 2) }} ج.م</h4>
                <p>إجمالي قيمة الشراء</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h4>{{ number_format($totalSaleValue ?? 0, 2) }} ج.م</h4>
                <p>إجمالي قيمة البيع</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>تفاصيل قيمة المخزون</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>اسم المنتج</th>
                        <th>الكمية</th>
                        <th>سعر الشراء</th>
                        <th>سعر البيع</th>
                        <th>قيمة الشراء</th>
                        <th>قيمة البيع</th>
                        <th>الربح المتوقع</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($inventoryValue))
                        @foreach($inventoryValue as $item)
                        <tr>
                            <td>{{ $item['product']->name }}</td>
                            <td>{{ $item['product']->quantity }}</td>
                            <td>{{ number_format($item['product']->purchase_price, 2) }} ج.م</td>
                            <td>{{ number_format($item['product']->sale_price, 2) }} ج.م</td>
                            <td>{{ number_format($item['purchase_value'], 2) }} ج.م</td>
                            <td>{{ number_format($item['sale_value'], 2) }} ج.م</td>
                            <td>{{ number_format($item['sale_value'] - $item['purchase_value'], 2) }} ج.م</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center">لا توجد منتجات</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
