
@extends('layouts.app')

@section('title', 'إدارة المنتجات')
@section('page-title', 'إدارة المنتجات')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.products.index') }}" class="nav-link active">
            <i class="fas fa-box me-2"></i>
            إدارة المنتجات
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.categories.index') }}" class="nav-link">
            <i class="fas fa-list me-2"></i>
            إدارة الفئات
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>إدارة المنتجات</h2>
    <div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary me-2">
            <i class="fas fa-plus me-2"></i>إضافة منتج جديد
        </a>
        <a href="{{ route('admin.products.low-stock') }}" class="btn btn-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>منتجات قليلة المخزون
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>الكود</th>
                        <th>اسم المنتج</th>
                        <th>الفئة</th>
                        <th>سعر الشراء</th>
                        <th>سعر البيع</th>
                        <th>الكمية</th>
                        <th>الحد الأدنى</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>{{ $product->code }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name ?? 'غير محدد' }}</td>
                        <td>{{ number_format($product->purchase_price, 2) }} ريال</td>
                        <td>{{ number_format($product->sale_price, 2) }} ريال</td>
                        <td>
                            <span class="badge bg-{{ $product->quantity <= $product->min_quantity ? 'danger' : 'success' }}">
                                {{ $product->quantity }}
                            </span>
                        </td>
                        <td>{{ $product->min_quantity }}</td>
                        <td>
                            @if($product->quantity <= $product->min_quantity)
                                <span class="badge bg-danger">مخزون منخفض</span>
                            @elseif($product->quantity == 0)
                                <span class="badge bg-warning">نفد المخزون</span>
                            @else
                                <span class="badge bg-success">متوفر</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $products->links() }}
    </div>
</div>
@endsection
