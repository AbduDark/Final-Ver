
@extends('layouts.app')

@section('title', 'التقرير اليومي - السوبر أدمن')
@section('page-title', 'التقرير اليومي لجميع المتاجر')

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
        <h2>التقرير اليومي لجميع المتاجر</h2>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>اختيار التاريخ</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('superadmin.reports.daily') }}">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">التاريخ</label>
                    <input type="date" name="date" class="form-control" value="{{ $date->format('Y-m-d') }}">
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

<!-- ملخص اليوم -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h5>إجمالي المبيعات</h5>
                <h3>{{ number_format($dailyData->sum('net_amount'), 2) }} ج.م</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h5>عدد الفواتير</h5>
                <h3>{{ $dailyData->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h5>متوسط الفاتورة</h5>
                <h3>{{ $dailyData->count() > 0 ? number_format($dailyData->sum('net_amount') / $dailyData->count(), 2) : 0 }} ج.م</h3>
            </div>
        </div>
    </div>
</div>

<!-- تفاصيل الفواتير اليومية -->
<div class="card">
    <div class="card-header">
        <h5>فواتير يوم {{ $date->format('Y-m-d') }}</h5>
    </div>
    <div class="card-body">
        @if($dailyData->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>رقم الفاتورة</th>
                        <th>المتجر</th>
                        <th>الكاشير</th>
                        <th>المبلغ</th>
                        <th>الوقت</th>
                        <th>عدد الأصناف</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyData as $invoice)
                    <tr>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->store->name }}</td>
                        <td>{{ $invoice->user->name }}</td>
                        <td>{{ number_format($invoice->net_amount, 2) }} ج.م</td>
                        <td>{{ $invoice->created_at->format('H:i') }}</td>
                        <td>{{ $invoice->items->count() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-4">
            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">لا توجد فواتير في هذا التاريخ</h5>
        </div>
        @endif
    </div>
</div>
@endsection
