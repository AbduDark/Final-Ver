
@extends('layouts.app')

@section('title', 'تنبيهات المخزون - السوبر أدمن')
@section('page-title', 'تنبيهات المخزون')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('superadmin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
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
        <h2>تنبيهات المخزون</h2>
    </div>
</div>

<!-- إحصائيات سريعة -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h5>منتجات نفدت</h5>
                <h3>{{ $criticalProducts->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h5>منتجات قليلة</h5>
                <h3>{{ $warningProducts->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-secondary text-white">
            <div class="card-body text-center">
                <h5>إجمالي التنبيهات</h5>
                <h3>{{ $lowStockProducts->count() }}</h3>
            </div>
        </div>
    </div>
</div>

@if($criticalProducts->count() > 0)
<!-- المنتجات التي نفدت تماماً -->
<div class="card mb-4">
    <div class="card-header bg-danger text-white">
        <h5><i class="fas fa-exclamation-circle me-2"></i>منتجات نفدت تماماً</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>اسم المنتج</th>
                        <th>الفئة</th>
                        <th>المتجر</th>
                        <th>الحد الأدنى</th>
                        <th>الكمية الحالية</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($criticalProducts as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name ?? 'غير محدد' }}</td>
                        <td>{{ $product->store->name }}</td>
                        <td>{{ $product->min_quantity }}</td>
                        <td class="text-danger fw-bold">{{ $product->quantity }}</td>
                        <td><span class="badge bg-danger">نفد</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@if($warningProducts->count() > 0)
<!-- المنتجات قليلة المخزون -->
<div class="card">
    <div class="card-header bg-warning text-dark">
        <h5><i class="fas fa-exclamation-triangle me-2"></i>منتجات قليلة المخزون</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>اسم المنتج</th>
                        <th>الفئة</th>
                        <th>المتجر</th>
                        <th>الحد الأدنى</th>
                        <th>الكمية الحالية</th>
                        <th>مستوى التحذير</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($warningProducts as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name ?? 'غير محدد' }}</td>
                        <td>{{ $product->store->name }}</td>
                        <td>{{ $product->min_quantity }}</td>
                        <td class="text-warning fw-bold">{{ $product->quantity }}</td>
                        <td>
                            @if($product->quantity <= $product->min_quantity * 0.5)
                                <span class="badge bg-danger">حرج جداً</span>
                            @else
                                <span class="badge bg-warning">قليل</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@if($lowStockProducts->count() == 0)
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
        <h4 class="text-success">ممتاز!</h4>
        <p class="text-muted">جميع المنتجات متوفرة بكميات كافية</p>
    </div>
</div>
@endif
@endsection
