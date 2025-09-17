
@extends('layouts.app')

@section('title', 'الإقفال اليومي')
@section('page-title', 'الإقفال اليومي للخزينة')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.treasury.index') }}" class="nav-link">
            <i class="fas fa-coins me-2"></i>
            الخزينة
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.treasury.daily-closing') }}" class="nav-link active">
            <i class="fas fa-cash-register me-2"></i>
            الإقفال اليومي
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">الإقفال اليومي - {{ now()->format('Y-m-d') }}</h2>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">الرصيد الحالي</h6>
                        <h4>{{ number_format($treasury->current_balance, 2) }} ج.م</h4>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-wallet fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">إيرادات اليوم</h6>
                        <h4>{{ number_format($todayIncome, 2) }} ج.م</h4>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-arrow-up fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">مصروفات اليوم</h6>
                        <h4>{{ number_format($todayExpense, 2) }} ج.م</h4>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-arrow-down fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">صافي اليوم</h6>
                        <h4>{{ number_format($todayIncome - $todayExpense, 2) }} ج.م</h4>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">معاملات اليوم</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>الوقت</th>
                        <th>النوع</th>
                        <th>المبلغ</th>
                        <th>الوصف</th>
                        <th>المستخدم</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($todayTransactions as $transaction)
                    <tr>
                        <td>{{ $transaction->created_at->format('H:i:s') }}</td>
                        <td>
                            <span class="badge bg-{{ $transaction->type === 'income' ? 'success' : 'danger' }}">
                                {{ $transaction->type === 'income' ? 'إيراد' : 'مصروف' }}
                            </span>
                        </td>
                        <td>{{ number_format($transaction->amount, 2) }} ج.م</td>
                        <td>{{ $transaction->description }}</td>
                        <td>{{ $transaction->user->name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12 text-center">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print me-2"></i>طباعة التقرير
        </button>
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    .sidebar, .btn, .card-header {
        display: none !important;
    }
    .main-content {
        margin: 0 !important;
        box-shadow: none !important;
    }
}
</style>
@endpush
