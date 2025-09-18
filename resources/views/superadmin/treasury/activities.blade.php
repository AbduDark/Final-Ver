
@extends('layouts.app')

@section('title', 'أنشطة الخزينة - السوبر أدمن')
@section('page-title', 'أنشطة الخزينة لجميع المتاجر')

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
        <a href="{{ route('superadmin.treasury.index') }}" class="nav-link active">
            <i class="fas fa-coins me-2"></i>
            إدارة الخزائن
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('superadmin.maintenance.index') }}" class="nav-link">
            <i class="fas fa-tools me-2"></i>
            طلبات الصيانة
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('superadmin.packages.index') }}" class="nav-link">
            <i class="fas fa-cube me-2"></i>
            إدارة الباقات
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('superadmin.reports.index') }}" class="nav-link">
            <i class="fas fa-chart-bar me-2"></i>
            التقارير والإحصائيات
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>أنشطة الخزينة لجميع المتاجر</h2>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>جميع المعاملات المالية</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>المتجر</th>
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
                        <td>{{ $transaction->treasury->store->name }}</td>
                        <td>
                            <span class="badge bg-{{ $transaction->type === 'income' ? 'success' : 'danger' }}">
                                {{ $transaction->type === 'income' ? 'إيرادات' : 'مصروفات' }}
                            </span>
                        </td>
                        <td>{{ number_format($transaction->amount, 2) }} ج.م</td>
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
