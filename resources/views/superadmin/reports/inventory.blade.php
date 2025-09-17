
@extends('layouts.app')

@section('title', 'تقرير المخزون - السوبر أدمن')
@section('page-title', 'تقرير المخزون لجميع المتاجر')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('superadmin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('superadmin.stores.index') }}" class="nav-link">
            <i class="fas fa-store me-2"></i>
            إدارة المتاجر
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('superadmin.users.index') }}" class="nav-link">
            <i class="fas fa-users me-2"></i>
            إدارة المستخدمين
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('superadmin.reports.index') }}" class="nav-link active">
            <i class="fas fa-chart-bar me-2"></i>
            التقارير والإحصائيات
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>تقرير المخزون لجميع المتاجر</h2>
    </div>
</div>

<!-- ملخص المخزون -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h5>إجمالي المنتجات</h5>
                <h3>{{ $products->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h5>منتجات قليلة المخزون</h5>
                <h3>{{ $lowStockProducts->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h5>إجمالي قيمة المخزون</h5>
                <h3>{{ number_format($totalValue, 2) }} ج.م</h3>
            </div>
        </div>
    </div>
</div>

@if($lowStockProducts->count() > 0)
<!-- منتجات قليلة المخزون -->
<div class="card mb-4">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0">منتجات قليلة المخزون (تحتاج إعادة تموين)</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>المتجر</th>
                        <th>اسم المنتج</th>
                        <th>الكمية الحالية</th>
                        <th>الحد الأدنى</th>
                        <th>الكمية المطلوبة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockProducts as $product)
                    <tr>
                        <td>{{ $product->store->name }}</td>
                        <td>{{ $product->name }}</td>
                        <td><span class="badge bg-danger">{{ $product->quantity }}</span></td>
                        <td>{{ $product->min_quantity }}</td>
                        <td><span class="badge bg-info">{{ $product->min_quantity - $product->quantity + 10 }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- جميع المنتجات -->
<div class="card">
    <div class="card-header">
        <h5>جميع المنتجات في جميع المتاجر</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>المتجر</th>
                        <th>اسم المنتج</th>
                        <th>الفئة</th>
                        <th>الكمية</th>
                        <th>سعر الشراء</th>
                        <th>سعر البيع</th>
                        <th>قيمة المخزون</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>{{ $product->store->name }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name ?? 'غير محدد' }}</td>
                        <td>
                            @if($product->quantity <= $product->min_quantity)
                            <span class="badge bg-danger">{{ $product->quantity }}</span>
                            @else
                            <span class="badge bg-success">{{ $product->quantity }}</span>
                            @endif
                        </td>
                        <td>{{ number_format($product->purchase_price, 2) }} ج.م</td>
                        <td>{{ number_format($product->sale_price, 2) }} ج.م</td>
                        <td>{{ number_format($product->quantity * $product->purchase_price, 2) }} ج.م</td>
                        <td>
                            @if($product->quantity <= $product->min_quantity)
                            <span class="badge bg-warning">قليل المخزون</span>
                            @elseif($product->quantity == 0)
                            <span class="badge bg-danger">نفد المخزون</span>
                            @else
                            <span class="badge bg-success">متوفر</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
