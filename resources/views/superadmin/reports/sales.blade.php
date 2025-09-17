
@extends('layouts.app')

@section('title', 'تقرير المبيعات - السوبر أدمن')
@section('page-title', 'تقرير المبيعات لجميع المتاجر')

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
        <h2>تقرير المبيعات لجميع المتاجر</h2>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>فلترة التقرير</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('superadmin.reports.sales') }}">
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
                        <i class="fas fa-search me-2"></i>بحث
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ملخص المبيعات -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h5>إجمالي المبيعات</h5>
                <h3>{{ number_format($totalSales, 2) }} ج.م</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h5>عدد الفواتير</h5>
                <h3>{{ $salesData->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h5>عدد المتاجر النشطة</h5>
                <h3>{{ $storesSales->count() }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- مبيعات المتاجر -->
<div class="card mb-4">
    <div class="card-header">
        <h5>مبيعات المتاجر</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>اسم المتجر</th>
                        <th>عدد الفواتير</th>
                        <th>إجمالي المبيعات</th>
                        <th>النسبة من المبيعات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($storesSales as $storeSale)
                    <tr>
                        <td>{{ $storeSale['store']->name }}</td>
                        <td>{{ $storeSale['count'] }}</td>
                        <td>{{ number_format($storeSale['total'], 2) }} ج.م</td>
                        <td>{{ $totalSales > 0 ? number_format(($storeSale['total'] / $totalSales) * 100, 1) : 0 }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- تفاصيل الفواتير -->
<div class="card">
    <div class="card-header">
        <h5>تفاصيل الفواتير</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>رقم الفاتورة</th>
                        <th>المتجر</th>
                        <th>الكاشير</th>
                        <th>المبلغ</th>
                        <th>التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salesData as $invoice)
                    <tr>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->store->name }}</td>
                        <td>{{ $invoice->user->name }}</td>
                        <td>{{ number_format($invoice->net_amount, 2) }} ج.م</td>
                        <td>{{ $invoice->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
