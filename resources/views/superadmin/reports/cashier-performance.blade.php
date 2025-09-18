
@extends('layouts.app')

@section('title', 'أداء الكاشيرز - السوبر أدمن')
@section('page-title', 'تقرير أداء الكاشيرز')

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
        <h2>تقرير أداء الكاشيرز</h2>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>فلترة التقرير</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('superadmin.reports.cashier-performance') }}">
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

<!-- إحصائيات الكاشيرز -->
<div class="card">
    <div class="card-header">
        <h5>أداء الكاشيرز</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>اسم الكاشير</th>
                        <th>المتجر</th>
                        <th>عدد الفواتير</th>
                        <th>إجمالي المبيعات</th>
                        <th>متوسط الفاتورة</th>
                        <th>التقييم</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cashierStats as $stat)
                    <tr>
                        <td>{{ $stat->user->name }}</td>
                        <td>{{ $stat->user->store->name }}</td>
                        <td>{{ $stat->total_invoices }}</td>
                        <td>{{ number_format($stat->total_sales, 2) }} ج.م</td>
                        <td>{{ number_format($stat->average_sale, 2) }} ج.م</td>
                        <td>
                            @if($stat->total_sales >= 5000)
                                <span class="badge bg-success">ممتاز</span>
                            @elseif($stat->total_sales >= 2000)
                                <span class="badge bg-warning">جيد</span>
                            @else
                                <span class="badge bg-secondary">مقبول</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
