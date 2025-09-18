
@extends('layouts.app')

@section('title', 'لوحة تحكم السوبر أدمن')
@section('page-title', 'لوحة التحكم الرئيسية')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('superadmin.dashboard') }}" class="nav-link active">
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
        <a href="{{ route('superadmin.packages.index') }}" class="nav-link">
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
<!-- الإحصائيات السريعة -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">إجمالي المتاجر</h6>
                        <h3 class="mb-0">{{ $totalStores }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-store fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">إجمالي المستخدمين</h6>
                        <h3 class="mb-0">{{ $totalUsers }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">مبيعات اليوم</h6>
                        <h3 class="mb-0">{{ number_format($todayRevenue, 2) }} ج.م</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">مبيعات الشهر</h6>
                        <h3 class="mb-0">{{ number_format($monthlyRevenue, 2) }} ج.م</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- الرسم البياني -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">مبيعات آخر 7 أيام</h5>
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- إحصائيات المتاجر -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">إحصائيات المتاجر</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>اسم المتجر</th>
                                <th>المستخدمين</th>
                                <th>المنتجات</th>
                                <th>الفواتير</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($storesStats as $store)
                            <tr>
                                <td>
                                    <a href="{{ route('superadmin.stores.show', $store) }}">
                                        {{ $store->name }}
                                    </a>
                                </td>
                                <td>{{ $store->users_count }}</td>
                                <td>{{ $store->products_count }}</td>
                                <td>{{ $store->invoices_count }}</td>
                                <td>
                                    <span class="badge bg-{{ $store->status === 'active' ? 'success' : 'danger' }}">
                                        {{ $store->status === 'active' ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">آخر الأنشطة</h5>
            </div>
            <div class="card-body">
                @foreach($recentActivities as $activity)
                <div class="activity-item mb-3">
                    <div class="d-flex">
                        <div class="activity-icon me-3">
                            <i class="fas fa-receipt text-primary"></i>
                        </div>
                        <div class="activity-content">
                            <p class="mb-1">{{ $activity->invoice_number }}</p>
                            <small class="text-muted">
                                {{ $activity->store->name }} - {{ $activity->user->name }}
                                <br>
                                {{ $activity->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- وصول سريع -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">وصول سريع</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('superadmin.stores.create') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-plus fa-2x mb-2 d-block"></i>
                            إضافة متجر جديد
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('superadmin.users.create') }}" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-user-plus fa-2x mb-2 d-block"></i>
                            إضافة مستخدم
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('superadmin.reports.sales') }}" class="btn btn-info btn-lg w-100">
                            <i class="fas fa-chart-bar fa-2x mb-2 d-block"></i>
                            تقرير المبيعات
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('superadmin.reports.activities') }}" class="btn btn-warning btn-lg w-100">
                            <i class="fas fa-history fa-2x mb-2 d-block"></i>
                            آخر الأنشطة
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// رسم بياني للمبيعات
const ctx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($weeklyChart->pluck('date')) !!},
        datasets: [{
            label: 'المبيعات (ج.م)',
            data: {!! json_encode($weeklyChart->pluck('total')) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush
