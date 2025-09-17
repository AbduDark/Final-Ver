
@extends('layouts.app')

@section('title', 'تقرير المنتجات')
@section('page-title', 'تقرير المنتجات')

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
        <h2>تقرير المنتجات</h2>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>تفاصيل المنتجات</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>اسم المنتج</th>
                        <th>الفئة</th>
                        <th>سعر الشراء</th>
                        <th>سعر البيع</th>
                        <th>الكمية</th>
                        <th>عدد المبيعات</th>
                        <th>هامش الربح</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($products))
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? 'غير محدد' }}</td>
                            <td>{{ number_format($product->purchase_price, 2) }} ج.م</td>
                            <td>{{ number_format($product->sale_price, 2) }} ج.م</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ $product->invoice_items_count ?? 0 }}</td>
                            <td>{{ number_format($product->sale_price - $product->purchase_price, 2) }} ج.م</td>
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
