
@extends('layouts.app')

@section('title', 'التقرير اليومي')
@section('page-title', 'التقرير اليومي')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.reports.index') }}" class="nav-link active">
            <i class="fas fa-chart-bar me-2"></i>
            التقارير
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>التقرير اليومي</h2>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>اختيار التاريخ</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.daily') }}">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">التاريخ</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date', now()->format('Y-m-d')) }}">
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

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h4>{{ number_format($totalRevenue ?? 0, 2) }} ج.م</h4>
                <p>إجمالي الإيرادات</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h4>{{ $totalInvoices ?? 0 }}</h4>
                <p>عدد الفواتير</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>فواتير اليوم</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>رقم الفاتورة</th>
                        <th>المبلغ</th>
                        <th>الكاشير</th>
                        <th>الوقت</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($dailyData))
                        @foreach($dailyData as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ number_format($invoice->net_amount, 2) }} ج.م</td>
                            <td>{{ $invoice->user->name }}</td>
                            <td>{{ $invoice->created_at->format('H:i') }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="text-center">لا توجد فواتير في هذا اليوم</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
