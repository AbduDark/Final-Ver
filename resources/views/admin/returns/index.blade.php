
@extends('layouts.app')

@section('title', 'إدارة المرتجعات')
@section('page-title', 'إدارة المرتجعات')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.returns.index') }}" class="nav-link active">
            <i class="fas fa-undo me-2"></i>
            إدارة المرتجعات
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>إدارة المرتجعات</h2>
    <a href="{{ route('admin.returns.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>إضافة مرتجع جديد
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>رقم الفاتورة</th>
                        <th>المنتج</th>
                        <th>الكمية</th>
                        <th>مبلغ الاسترداد</th>
                        <th>السبب</th>
                        <th>المستخدم</th>
                        <th>تاريخ المرتجع</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($returns as $return)
                    <tr>
                        <td>{{ $return->invoice->invoice_number }}</td>
                        <td>{{ $return->product->name }}</td>
                        <td>{{ $return->quantity }}</td>
                        <td>{{ number_format($return->refund_amount, 2) }} ج.م</td>
                        <td>{{ $return->reason }}</td>
                        <td>{{ $return->user->name }}</td>
                        <td>{{ $return->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $returns->links() }}
    </div>
</div>
@endsection
