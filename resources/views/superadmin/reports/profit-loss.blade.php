
@extends('layouts.app')

@section('title', 'تقرير الأرباح والخسائر - السوبر أدمن')
@section('page-title', 'تقرير الأرباح والخسائر')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('superadmin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
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
        <h2>تقرير الأرباح والخسائر</h2>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>فلترة التقرير</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('superadmin.reports.profit-loss') }}">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">من تاريخ</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">إلى تاريخ</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
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

<!-- ملخص الأرباح -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h5>إجمالي الإيرادات</h5>
                <h3>{{ number_format($totalRevenue, 2) }} ج.م</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h5>إجمالي التكاليف</h5>
                <h3>{{ number_format($totalCost, 2) }} ج.م</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h5>صافي الربح</h5>
                <h3>{{ number_format($grossProfit, 2) }} ج.م</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h5>هامش الربح</h5>
                <h3>{{ number_format($profitMargin, 1) }}%</h3>
            </div>
        </div>
    </div>
</div>

<!-- تفاصيل الأرباح حسب المتاجر -->
<div class="card">
    <div class="card-header">
        <h5>تفاصيل الأرباح حسب المتاجر</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>اسم المتجر</th>
                        <th>الإيرادات</th>
                        <th>التكاليف</th>
                        <th>صافي الربح</th>
                        <th>هامش الربح</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($storesProfits as $storeProfit)
                    <tr>
                        <td>{{ $storeProfit['store']->name }}</td>
                        <td>{{ number_format($storeProfit['revenue'], 2) }} ج.م</td>
                        <td>{{ number_format($storeProfit['cost'], 2) }} ج.م</td>
                        <td class="{{ $storeProfit['profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($storeProfit['profit'], 2) }} ج.م
                        </td>
                        <td class="{{ $storeProfit['margin'] >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($storeProfit['margin'], 1) }}%
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
