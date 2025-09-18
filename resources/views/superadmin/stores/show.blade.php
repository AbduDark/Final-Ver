
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

<!-- إحصائيات سريعة -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>{{ $store->users_count }}</h5>
                        <p class="mb-0">المستخدمين</p>
                    </div>
                    <i class="fas fa-users fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>{{ $store->products_count }}</h5>
                        <p class="mb-0">المنتجات</p>
                    </div>
                    <i class="fas fa-box fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>{{ $store->invoices_count }}</h5>
                        <p class="mb-0">الفواتير</p>
                    </div>
                    <i class="fas fa-receipt fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>{{ number_format($treasuryBalance, 2) }} ج.م</h5>
                        <p class="mb-0">رصيد الخزينة</p>
                    </div>
                    <i class="fas fa-wallet fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- معلومات المتجر -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5><i class="fas fa-store me-2"></i>معلومات المتجر</h5>
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
                    <tr>
                        <td><strong>تاريخ الإنشاء:</strong></td>
                        <td>{{ $store->created_at->format('Y-m-d') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- تفاصيل الخزينة -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5><i class="fas fa-wallet me-2"></i>تفاصيل الخزينة</h5>
            </div>
            <div class="card-body">
                @if($treasury)
                <table class="table table-borderless">
                    <tr>
                        <td><strong>الرصيد الحالي:</strong></td>
                        <td class="fw-bold text-primary">{{ number_format($treasury->current_balance, 2) }} ج.م</td>
                    </tr>
                    <tr>
                        <td><strong>آخر معاملة:</strong></td>
                        <td>
                            <span class="badge bg-{{ $treasury->last_transaction_type === 'income' ? 'success' : 'danger' }}">
                                {{ $treasury->last_transaction_type === 'income' ? 'إيداع' : 'سحب' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>مبلغ آخر معاملة:</strong></td>
                        <td>{{ number_format($treasury->last_transaction_amount, 2) }} ج.م</td>
                    </tr>
                    <tr>
                        <td><strong>تاريخ آخر معاملة:</strong></td>
                        <td>{{ $treasury->last_transaction_date ? $treasury->last_transaction_date->format('Y-m-d H:i') : 'لا يوجد' }}</td>
                    </tr>
                </table>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <h6 class="text-success">{{ number_format($todayIncome, 2) }} ج.م</h6>
                        <small>إيرادات اليوم</small>
                    </div>
                    <div class="col-6">
                        <h6 class="text-danger">{{ number_format($todayExpenses, 2) }} ج.م</h6>
                        <small>مصروفات اليوم</small>
                    </div>
                </div>
                @else
                <p class="text-muted">لا توجد خزينة لهذا المتجر</p>
                @endif
            </div>
        </div>
    </div>

    <!-- إحصائيات إضافية -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5><i class="fas fa-chart-pie me-2"></i>إحصائيات إضافية</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>فواتير اليوم:</strong></td>
                        <td><span class="badge bg-info">{{ $todayInvoicesCount }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>مبيعات اليوم:</strong></td>
                        <td class="fw-bold text-success">{{ number_format($todaySales, 2) }} ج.م</td>
                    </tr>
                    <tr>
                        <td><strong>منتجات قليلة المخزون:</strong></td>
                        <td><span class="badge bg-warning">{{ $lowStockProducts }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>طلبات صيانة معلقة:</strong></td>
                        <td><span class="badge bg-secondary">{{ $pendingMaintenance }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>عمليات الإرجاع:</strong></td>
                        <td><span class="badge bg-danger">{{ $returnsCount }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- مستخدمي المتجر -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5><i class="fas fa-users me-2"></i>مستخدمي المتجر</h5>
                <a href="{{ route('superadmin.stores.users', $store) }}" class="btn btn-sm btn-primary">
                    عرض الكل
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>البريد الإلكتروني</th>
                                <th>النوع</th>
                                <th>تاريخ الإنضمام</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($store->users->take(5) as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->type === 'admin' ? 'primary' : 'success' }}">
                                        {{ $user->type === 'admin' ? 'مدير' : 'كاشير' }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- آخر الفواتير -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-receipt me-2"></i>آخر الفواتير</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>رقم الفاتورة</th>
                                <th>الكاشير</th>
                                <th>المبلغ الإجمالي</th>
                                <th>المبلغ الصافي</th>
                                <th>التاريخ</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($store->invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->invoice_number }}</td>
                                <td>{{ $invoice->user->name }}</td>
                                <td>{{ number_format($invoice->total, 2) }} ج.م</td>
                                <td>{{ number_format($invoice->net_amount, 2) }} ج.م</td>
                                <td>{{ $invoice->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <span class="badge bg-success">مدفوعة</span>
                                </td>
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
