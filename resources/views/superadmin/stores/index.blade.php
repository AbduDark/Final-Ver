
@extends('layouts.app')

@section('title', 'إدارة المتاجر')
@section('page-title', 'إدارة المتاجر')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('superadmin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('superadmin.stores.index') }}" class="nav-link active">
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
        <a href="{{ route('superadmin.reports.index') }}" class="nav-link">
            <i class="fas fa-chart-bar me-2"></i>
            التقارير والإحصائيات
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>إدارة المتاجر</h2>
    <a href="{{ route('superadmin.stores.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>إضافة متجر جديد
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
                        <th>اسم المتجر</th>
                        <th>العنوان</th>
                        <th>الهاتف</th>
                        <th>البريد الإلكتروني</th>
                        <th>المستخدمين</th>
                        <th>المنتجات</th>
                        <th>الفواتير</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stores as $store)
                    <tr>
                        <td>{{ $store->name }}</td>
                        <td>{{ $store->address }}</td>
                        <td>{{ $store->phone }}</td>
                        <td>{{ $store->email }}</td>
                        <td>{{ $store->users_count }}</td>
                        <td>{{ $store->products_count }}</td>
                        <td>{{ $store->invoices_count }}</td>
                        <td>
                            <span class="badge bg-{{ $store->status === 'active' ? 'success' : 'danger' }}">
                                {{ $store->status === 'active' ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('superadmin.stores.show', $store) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('superadmin.stores.edit', $store) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('superadmin.stores.users', $store) }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-users"></i>
                                </a>
                                <form action="{{ route('superadmin.stores.destroy', $store) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('هل أنت متأكد من حذف هذا المتجر؟')">
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

        {{ $stores->links() }}
    </div>
</div>
@endsection
