
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

<!-- فيلتر البحث المتقدم -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.products.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" 
                           placeholder="ابحث بالاسم أو الكود" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="category" class="form-select">
                        <option value="">كل الفئات</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="stock_status" class="form-select">
                        <option value="">كل المخزون</option>
                        <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>مخزون منخفض</option>
                        <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>نفد المخزون</option>
                        <option value="available" {{ request('stock_status') == 'available' ? 'selected' : '' }}>متوفر</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="min_price" class="form-control" 
                           placeholder="أقل سعر" value="{{ request('min_price') }}">
                </div>
                <div class="col-md-2">
                    <input type="number" name="max_price" class="form-control" 
                           placeholder="أعلى سعر" value="{{ request('max_price') }}">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary">بحث</button>
                </div>
            </div>
        </form>
    </div>
</div>

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
                        <td>{{ number_format($product->purchase_price, 2) }} ج.م</td>
                        <td>{{ number_format($product->sale_price, 2) }} ج.م</td>
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
