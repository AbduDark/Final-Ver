
@extends('layouts.app')

@section('title', 'إدارة الباقات')
@section('page-title', 'إدارة الباقات')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.packages.index') }}" class="nav-link active">
            <i class="fas fa-cube me-2"></i>
            الباقات
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>إدارة الباقات</h2>
    <a href="{{ route('admin.packages.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>إضافة باقة جديدة
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
                        <th>اسم الباقة</th>
                        <th>الشبكة</th>
                        <th>سعر الشراء</th>
                        <th>سعر البيع</th>
                        <th>هامش الربح</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($packages as $package)
                    <tr>
                        <td>{{ $package->name }}</td>
                        <td>{{ $package->network }}</td>
                        <td>{{ number_format($package->purchase_price, 2) }} ج.م</td>
                        <td>{{ number_format($package->sale_price, 2) }} ج.م</td>
                        <td>
                            <span class="badge bg-success">
                                {{ number_format($package->sale_price - $package->purchase_price, 2) }} ج.م
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('هل أنت متأكد من حذف هذه الباقة؟')">
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

        {{ $packages->links() }}
    </div>
</div>
@endsection
