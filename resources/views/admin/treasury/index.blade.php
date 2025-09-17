
@extends('layouts.app')

@section('title', 'إدارة الخزينة')
@section('page-title', 'إدارة الخزينة')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.treasury.index') }}" class="nav-link active">
            <i class="fas fa-coins me-2"></i>
            الخزينة
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6>الرصيد الحالي</h6>
                        <h3>{{ number_format($treasury->current_balance, 2) }} ريال</h3>
                    </div>
                    <div>
                        <i class="fas fa-wallet fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6>آخر معاملة</h6>
                        <p>{{ $treasury->last_transaction_type === 'income' ? 'إيرادات' : 'مصروفات' }}</p>
                        <p>{{ number_format($treasury->last_transaction_amount, 2) }} ريال</p>
                    </div>
                    <div>
                        <i class="fas fa-exchange-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>إضافة معاملة يدوية</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.treasury.transaction') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <select name="type" class="form-control" required>
                                <option value="">نوع المعاملة</option>
                                <option value="income">إيرادات</option>
                                <option value="expense">مصروفات</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="amount" step="0.01" class="form-control" placeholder="المبلغ" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="description" class="form-control" placeholder="الوصف" required>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">إضافة</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>حركات الخزينة</h5>
        <a href="{{ route('admin.treasury.daily-closing') }}" class="btn btn-warning btn-sm">
            <i class="fas fa-calculator me-2"></i>إقفال اليوم
        </a>
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
                        <th>التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                    <tr>
                        <td>
                            <span class="badge bg-{{ $transaction->type === 'income' ? 'success' : 'danger' }}">
                                {{ $transaction->type === 'income' ? 'إيرادات' : 'مصروفات' }}
                            </span>
                        </td>
                        <td>{{ number_format($transaction->amount, 2) }} ريال</td>
                        <td>{{ $transaction->description }}</td>
                        <td>{{ $transaction->user->name }}</td>
                        <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $transactions->links() }}
    </div>
</div>
@endsection
