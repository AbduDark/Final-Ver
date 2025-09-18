
@extends('layouts.app')

@section('title', 'إدارة الباقات - السوبر أدمن')
@section('page-title', 'إدارة الباقات لجميع المتاجر')

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
        <a href="{{ route('superadmin.treasury.index') }}" class="nav-link">
            <i class="fas fa-coins me-2"></i>
            إدارة الخزائن
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('superadmin.maintenance.index') }}" class="nav-link">
            <i class="fas fa-tools me-2"></i>
            طلبات الصيانة
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('superadmin.packages.index') }}" class="nav-link active">
            <i class="fas fa-cube me-2"></i>
            إدارة الباقات
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
<div class="row mb-4">
    <div class="col-12">
        <h2>إدارة الباقات لجميع المتاجر</h2>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>جميع الباقات</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>اسم الباقة</th>
                        <th>الشبكة</th>
                        <th>المتجر</th>
                        <th>سعر الشراء</th>
                        <th>سعر البيع</th>
                        <th>هامش الربح</th>
                        <th>تاريخ الإضافة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($packages as $package)
                    <tr>
                        <td>{{ $package->name }}</td>
                        <td>{{ $package->network }}</td>
                        <td>{{ $package->store->name }}</td>
                        <td>{{ number_format($package->purchase_price, 2) }} ج.م</td>
                        <td>{{ number_format($package->sale_price, 2) }} ج.م</td>
                        <td>
                            <span class="badge bg-success">
                                {{ number_format($package->sale_price - $package->purchase_price, 2) }} ج.م
                            </span>
                        </td>
                        <td>{{ $package->created_at->format('Y-m-d') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $packages->links() }}
    </div>
</div>
@endsection
