
@extends('layouts.app')

@section('title', 'تقرير الإقفال اليومي')
@section('page-title', 'تقرير الإقفال اليومي')

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
        <h2>تقرير الإقفال اليومي - {{ now()->format('Y-m-d') }}</h2>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">الرصيد الحالي</h6>
                        <h4>{{ number_format($treasury->current_balance ?? 0, 2) }} ج.م</h4>
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
                        <h4>{{ number_format($todayIncome ?? 0, 2) }} ج.م</h4>
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
                        <h4>{{ number_format($todayExpense ?? 0, 2) }} ج.م</h4>
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
                        <h4>{{ number_format(($todayIncome ?? 0) - ($todayExpense ?? 0), 2) }} ج.م</h4>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calculator fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>معاملات اليوم</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>النوع</th>
                        <th>المبلغ</th>
                        <th>الوصف</th>
                        <th>المستخدم</th>
                        <th>الوقت</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($todayTransactions))
                        @foreach($todayTransactions as $transaction)
                        <tr>
                            <td>
                                <span class="badge bg-{{ $transaction->type === 'income' ? 'success' : 'danger' }}">
                                    {{ $transaction->type === 'income' ? 'إيرادات' : 'مصروفات' }}
                                </span>
                            </td>
                            <td>{{ number_format($transaction->amount, 2) }} ج.م</td>
                            <td>{{ $transaction->description }}</td>
                            <td>{{ $transaction->user->name }}</td>
                            <td>{{ $transaction->created_at->format('H:i') }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">لا توجد معاملات اليوم</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
