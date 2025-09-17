
@extends('layouts.app')

@section('title', 'التقرير الأسبوعي - السوبر أدمن')
@section('page-title', 'التقرير الأسبوعي لجميع المتاجر')

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
        <h2>التقرير الأسبوعي لجميع المتاجر</h2>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>اختيار الأسبوع</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('superadmin.reports.weekly') }}">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">من تاريخ</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startOfWeek->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">إلى تاريخ</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endOfWeek->format('Y-m-d') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>عرض التقرير
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ملخص الأسبوع -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h5>إجمالي المبيعات الأسبوعية</h5>
                <h3>{{ number_format($totalWeeklyRevenue, 2) }} ج.م</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h5>عدد الفواتير</h5>
                <h3>{{ $weeklyData->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h5>متوسط المبيعات اليومية</h5>
                <h3>{{ number_format($totalWeeklyRevenue / 7, 2) }} ج.م</h3>
            </div>
        </div>
    </div>
</div>

<!-- التفصيل اليومي -->
<div class="card mb-4">
    <div class="card-header">
        <h5>التفصيل اليومي</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>عدد الفواتير</th>
                        <th>إجمالي المبيعات</th>
                        <th>النسبة من الأسبوع</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyBreakdown as $date => $data)
                    <tr>
                        <td>{{ $date }}</td>
                        <td>{{ $data['count'] }}</td>
                        <td>{{ number_format($data['total'], 2) }} ج.م</td>
                        <td>{{ $totalWeeklyRevenue > 0 ? number_format(($data['total'] / $totalWeeklyRevenue) * 100, 1) : 0 }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
