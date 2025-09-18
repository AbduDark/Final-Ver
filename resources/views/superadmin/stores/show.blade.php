
@extends('layouts.app')

@section('title', 'تفاصيل المتجر')
@section('page-title', 'تفاصيل المتجر: ' . $store->name)

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('superadmin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('superadmin.stores.index') }}" class="nav-link active">
            <i class="fas fa-store me-2"></i>
            إدارة المتاجر
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>تفاصيل المتجر: {{ $store->name }}</h2>
    <div>
        <a href="{{ route('superadmin.stores.edit', $store) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>تعديل
        </a>
        <a href="{{ route('superadmin.stores.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>العودة
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>معلومات المتجر</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>اسم المتجر:</strong></td>
                        <td>{{ $store->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>العنوان:</strong></td>
                        <td>{{ $store->address }}</td>
                    </tr>
                    <tr>
                        <td><strong>الهاتف:</strong></td>
                        <td>{{ $store->phone }}</td>
                    </tr>
                    <tr>
                        <td><strong>البريد الإلكتروني:</strong></td>
                        <td>{{ $store->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>الحالة:</strong></td>
                        <td>
                            <span class="badge bg-{{ $store->status === 'active' ? 'success' : 'danger' }}">
                                {{ $store->status === 'active' ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>الإحصائيات</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <h4 class="text-primary">{{ $store->users_count }}</h4>
                        <p>المستخدمين</p>
                    </div>
                    <div class="col-4">
                        <h4 class="text-success">{{ $store->products_count }}</h4>
                        <p>المنتجات</p>
                    </div>
                    <div class="col-4">
                        <h4 class="text-info">{{ $store->invoices_count }}</h4>
                        <p>الفواتير</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>آخر الفواتير</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>رقم الفاتورة</th>
                                <th>الكاشير</th>
                                <th>المبلغ</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($store->invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->invoice_number }}</td>
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
    </div>
</div>
@endsection
