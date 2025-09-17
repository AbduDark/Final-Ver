@extends('layouts.app')

@section('title', 'لوحة تحكم المتجر - ' . ($store->name ?? 'المتجر'))
@section('page-title', 'لوحة تحكم المتجر')

@section('sidebar')
<nav class="nav flex-column">
    <a class="nav-link active" href="{{ route('admin.dashboard') }}">
        <i class="fas fa-tachometer-alt me-2"></i>
        لوحة التحكم
    </a>

    <a class="nav-link" href="{{ route('admin.products.index') }}">
        <i class="fas fa-box me-2"></i>
        المنتجات
    </a>

    <a class="nav-link" href="{{ route('admin.categories.index') }}">
        <i class="fas fa-tags me-2"></i>
        الفئات
    </a>

    <a class="nav-link" href="{{ route('admin.invoices.index') }}">
        <i class="fas fa-file-invoice me-2"></i>
        الفواتير
    </a>

    <a class="nav-link" href="{{ route('admin.returns.index') }}">
        <i class="fas fa-undo me-2"></i>
        المرتجعات
    </a>

    <a class="nav-link" href="{{ route('admin.maintenance.index') }}">
        <i class="fas fa-tools me-2"></i>
        الصيانة
    </a>

    <a class="nav-link" href="{{ route('admin.transfers.index') }}">
        <i class="fas fa-exchange-alt me-2"></i>
        التحويلات
    </a>

    <a class="nav-link" href="{{ route('admin.packages.index') }}">
        <i class="fas fa-box-open me-2"></i>
        الباقات
    </a>

    <a class="nav-link" href="{{ route('admin.treasury.index') }}">
        <i class="fas fa-cash-register me-2"></i>
        الخزينة
    </a>

    <a class="nav-link" href="{{ route('admin.reports.index') }}">
        <i class="fas fa-chart-bar me-2"></i>
        التقارير
    </a>
</nav>
@endsection

@section('content')
<!-- معلومات المتجر -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-store me-2"></i>
                    {{ $store->name ?? 'اسم المتجر' }}
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-1">{{ $store->address ?? 'عنوان المتجر' }}</p>
                <p class="text-muted mb-0">{{ $store->phone ?? 'رقم الهاتف' }}</p>
            </div>
        </div>
    </div>
</div>

<!-- الإحصائيات -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-box fa-2x mb-3"></i>
                <h4>{{ $totalProducts ?? 0 }}</h4>
                <p class="mb-0">إجمالي المنتجات</p>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                <h4>{{ $lowStockProducts ?? 0 }}</h4>
                <p class="mb-0">منتجات قليلة المخزون</p>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-file-invoice fa-2x mb-3"></i>
                <h4>{{ $todayInvoices ?? 0 }}</h4>
                <p class="mb-0">فواتير اليوم</p>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-dollar-sign fa-2x mb-3"></i>
                <h4>{{ number_format($todaySales ?? 0, 2) }} ج.م</h4>
                <p class="mb-0">مبيعات اليوم</p>
            </div>
        </div>
    </div>
</div>

<!-- إحصائيات إضافية -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card bg-secondary text-white">
            <div class="card-body text-center">
                <i class="fas fa-tags fa-2x mb-3"></i>
                <h4>{{ $totalCategories ?? 0 }}</h4>
                <p class="mb-0">الفئات</p>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card bg-danger text-white">
            <div class="card-body text-center">
                <i class="fas fa-undo fa-2x mb-3"></i>
                <h4>{{ $totalReturns ?? 0 }}</h4>
                <p class="mb-0">المرتجعات</p>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card bg-dark text-white">
            <div class="card-body text-center">
                <i class="fas fa-tools fa-2x mb-3"></i>
                <h4>{{ $pendingMaintenance ?? 0 }}</h4>
                <p class="mb-0">صيانة معلقة</p>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card" style="background: linear-gradient(45deg, #667eea, #764ba2);">
            <div class="card-body text-center text-white">
                <i class="fas fa-chart-line fa-2x mb-3"></i>
                <h4>100%</h4>
                <p class="mb-0">كفاءة النظام</p>
            </div>
        </div>
    </div>
</div>

<!-- أحدث الفواتير والمنتجات قليلة المخزون -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-file-invoice me-2"></i>
                    أحدث الفواتير
                </h5>
            </div>
            <div class="card-body">
                @if(isset($recentInvoices) && $recentInvoices->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>رقم الفاتورة</th>
                                    <th>العميل</th>
                                    <th>المبلغ</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentInvoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->invoice_number }}</td>
                                        <td>{{ $invoice->customer_name ?? 'عميل نقدي' }}</td>
                                        <td>{{ number_format($invoice->total, 2) }}</td>
                                        <td>{{ $invoice->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">لا توجد فواتير حتى الآن</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2 text-warning"></i>
                    منتجات قليلة المخزون
                </h5>
            </div>
            <div class="card-body">
                @if(isset($lowStockProductsList) && $lowStockProductsList->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>المنتج</th>
                                    <th>الفئة</th>
                                    <th>المخزون</th>
                                    <th>الحد الأدنى</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockProductsList as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->category->name ?? 'غير محدد' }}</td>
                                        <td>
                                            <span class="badge bg-danger">{{ $product->stock }}</span>
                                        </td>
                                        <td>{{ $product->min_stock }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">جميع المنتجات لديها مخزون كافي</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- روابط سريعة -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    روابط سريعة
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-plus me-2"></i>
                            إضافة منتج
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.invoices.create') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-file-invoice me-2"></i>
                            فاتورة جديدة
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.products.low-stock') }}" class="btn btn-outline-warning w-100">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            المخزون المنخفض
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.reports.daily') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-chart-bar me-2"></i>
                            تقرير يومي
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // يمكن إضافة JavaScript للرسوم البيانية هنا
</script>
@endpush