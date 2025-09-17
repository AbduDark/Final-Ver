
@extends('layouts.app')

@section('title', 'المنتجات قليلة المخزون')
@section('page-title', 'المنتجات قليلة المخزون')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.products.index') }}" class="nav-link">
            <i class="fas fa-box me-2"></i>
            المنتجات
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.products.low-stock') }}" class="nav-link active">
            <i class="fas fa-exclamation-triangle me-2"></i>
            المنتجات قليلة المخزون
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>المنتجات قليلة المخزون</h2>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-right me-2"></i>رجوع للمنتجات
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($products->count() > 0)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                يوجد {{ $products->count() }} منتج يحتاج إلى إعادة تجديد المخزون
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>الكود</th>
                            <th>اسم المنتج</th>
                            <th>الفئة</th>
                            <th>الكمية الحالية</th>
                            <th>الحد الأدنى</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr class="{{ $product->quantity == 0 ? 'table-danger' : 'table-warning' }}">
                            <td>{{ $product->code }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? 'غير محدد' }}</td>
                            <td>
                                <span class="badge bg-{{ $product->quantity == 0 ? 'danger' : 'warning' }}">
                                    {{ $product->quantity }}
                                </span>
                            </td>
                            <td>{{ $product->min_quantity }}</td>
                            <td>
                                @if($product->quantity == 0)
                                    <span class="badge bg-danger">نفد المخزون</span>
                                @else
                                    <span class="badge bg-warning">مخزون منخفض</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit me-1"></i>تحديث المخزون
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $products->links() }}
        @else
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                جميع المنتجات تحتوي على كمية كافية في المخزون
            </div>
        @endif
    </div>
</div>
@endsection
