
@extends('layouts.app')

@section('title', 'إدارة الفئات')
@section('page-title', 'إدارة الفئات')

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
            إدارة المنتجات
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.categories.index') }}" class="nav-link active">
            <i class="fas fa-list me-2"></i>
            إدارة الفئات
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>إدارة الفئات</h2>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>إضافة فئة جديدة
    </a>
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
                        <th>اسم الفئة</th>
                        <th>الوصف</th>
                        <th>عدد المنتجات</th>
                        <th>تاريخ الإنشاء</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description ?? 'لا يوجد وصف' }}</td>
                        <td>{{ $category->products_count ?? 0 }}</td>
                        <td>{{ $category->created_at->format('Y-m-d') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('هل أنت متأكد من حذف هذه الفئة؟')">
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

        {{ $categories->links() }}
    </div>
</div>
@endsection
