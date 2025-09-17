
@extends('layouts.app')

@section('title', 'تقرير المخزون')
@section('page-title', 'تقرير المخزون')

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
        <h2>تقرير المخزون</h2>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h4>{{ $totalProducts ?? 0 }}</h4>
                <p>إجمالي المنتجات</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h4>{{ $lowStockProducts ?? 0 }}</h4>
                <p>منتجات قليلة المخزون</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h4>{{ number_format($totalValue ?? 0, 2) }} ج.م</h4>
                <p>قيمة المخزون</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>تفاصيل المخزون</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>اسم المنتج</th>
                        <th>الفئة</th>
                        <th>الكمية</th>
                        <th>الحد الأدنى</th>
                        <th>سعر الشراء</th>
                        <th>قيمة المخزون</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($products))
                        @foreach($products as $product)
                        <tr class="{{ $product->quantity <= $product->min_quantity ? 'table-warning' : '' }}">
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? 'غير محدد' }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ $product->min_quantity }}</td>
                            <td>{{ number_format($product->purchase_price, 2) }} ج.م</td>
                            <td>{{ number_format($product->quantity * $product->purchase_price, 2) }} ج.م</td>
                            <td>
                                @if($product->quantity <= $product->min_quantity)
                                    <span class="badge bg-warning">قليل المخزون</span>
                                @else
                                    <span class="badge bg-success">متوفر</span>
                                @endif
                            </td>
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
