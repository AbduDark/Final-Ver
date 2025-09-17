
@extends('layouts.app')

@section('title', 'التقرير الشهري - السوبر أدمن')
@section('page-title', 'التقرير الشهري لجميع المتاجر')

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
        <h2>التقرير الشهري لجميع المتاجر</h2>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>اختيار الشهر</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('superadmin.reports.monthly') }}">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">الشهر</label>
                    <input type="month" name="month" class="form-control" value="{{ $month }}">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>عرض التقرير
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ملخص الشهر -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h5>إجمالي المبيعات الشهرية</h5>
                <h3>{{ number_format($totalMonthlyRevenue, 2) }} ج.م</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h5>عدد الفواتير</h5>
                <h3>{{ $monthlyData->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h5>متوسط المبيعات اليومية</h5>
                <h3>{{ number_format($totalMonthlyRevenue / 30, 2) }} ج.م</h3>
            </div>
        </div>
    </div>
</div>

<!-- إحصائيات المتاجر الشهرية -->
<div class="card mb-4">
    <div class="card-header">
        <h5>إحصائيات المتاجر الشهرية</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>اسم المتجر</th>
                        <th>عدد الفواتير</th>
                        <th>إجمالي المبيعات</th>
                        <th>النسبة من الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($storesMonthlyStats as $storeStat)
                    <tr>
                        <td>{{ $storeStat['store']->name }}</td>
                        <td>{{ $storeStat['count'] }}</td>
                        <td>{{ number_format($storeStat['total'], 2) }} ج.م</td>
                        <td>{{ $totalMonthlyRevenue > 0 ? number_format(($storeStat['total'] / $totalMonthlyRevenue) * 100, 1) : 0 }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- التفصيل الأسبوعي -->
<div class="card">
    <div class="card-header">
        <h5>التفصيل الأسبوعي</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>الأسبوع</th>
                        <th>عدد الفواتير</th>
                        <th>إجمالي المبيعات</th>
                        <th>النسبة من الشهر</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($weeklyBreakdown as $week => $data)
                    <tr>
                        <td>الأسبوع {{ $week }}</td>
                        <td>{{ $data['count'] }}</td>
                        <td>{{ number_format($data['total'], 2) }} ج.م</td>
                        <td>{{ $totalMonthlyRevenue > 0 ? number_format(($data['total'] / $totalMonthlyRevenue) * 100, 1) : 0 }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
