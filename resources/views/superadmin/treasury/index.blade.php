
@extends('layouts.app')

@section('title', 'إدارة الخزائن - السوبر أدمن')
@section('page-title', 'إدارة الخزائن لجميع المتاجر')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('superadmin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('superadmin.treasury.index') }}" class="nav-link active">
            <i class="fas fa-coins me-2"></i>
            إدارة الخزائن
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
                        <h6>إجمالي الأرصدة</h6>
                        <h3>{{ number_format($totalBalance, 2) }} ج.م</h3>
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
                        <h6>عدد المتاجر</h6>
                        <h3>{{ $treasuries->count() }}</h3>
                    </div>
                    <div>
                        <i class="fas fa-store fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between">
        <h5>خزائن المتاجر</h5>
        <a href="{{ route('superadmin.treasury.activities') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-list me-2"></i>عرض جميع الأنشطة
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>اسم المتجر</th>
                        <th>الرصيد الحالي</th>
                        <th>آخر معاملة</th>
                        <th>نوع آخر معاملة</th>
                        <th>التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($treasuries as $treasury)
                    <tr>
                        <td>{{ $treasury->store->name }}</td>
                        <td>{{ number_format($treasury->current_balance, 2) }} ج.م</td>
                        <td>{{ number_format($treasury->last_transaction_amount, 2) }} ج.م</td>
                        <td>
                            <span class="badge bg-{{ $treasury->last_transaction_type === 'income' ? 'success' : 'danger' }}">
                                {{ $treasury->last_transaction_type === 'income' ? 'إيرادات' : 'مصروفات' }}
                            </span>
                        </td>
                        <td>{{ $treasury->updated_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
