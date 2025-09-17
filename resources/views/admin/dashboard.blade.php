@extends('layouts.app')

@section('title', 'لوحة تحكم الأدمن')
@section('page-title', 'لوحة التحكم - ' . $store->name)

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link active">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('products.index') }}" class="nav-link">
            <i class="fas fa-box me-2"></i>
            إدارة المنتجات
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('categories.index') }}" class="nav-link">
            <i class="fas fa-tags me-2"></i>
            إدارة الفئات
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('products.low-stock') }}" class="nav-link">
            <i class="fas fa-exclamation-triangle me-2"></i>
            المنتجات المنخفضة
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.reports') }}" class="nav-link">
            <i class="fas fa-chart-bar me-2"></i>
            التقارير والإحصائيات
        </a>
    </li>
</ul>
@endsection

@section('content')
<!-- الإحصائيات السريعة -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">مبيعات اليوم</h6>
                        <h3 class="mb-0">{{ number_format($todaySales, 2) }} ر.س</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">منتجات منخفضة</h6>
                        <h3 class="mb-0">{{ $lowStockProducts }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">صيانات معلقة</h6>
                        <h3 class="mb-0">{{ $pendingMaintenance }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-tools fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">إجمالي المنتجات</h6>
                        <h3 class="mb-0">{{ $totalProducts }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-box fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- الرسوم البيانية والتنبيهات -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">مبيعات آخر 7 أيام</h5>
            </div>
            <div class="card-body">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">تنبيهات المخزون</h5>
            </div>
            <div class="card-body">
                @if($lowStockList->count() > 0)
                    @foreach($lowStockList as $product)
                        <div class="alert alert-warning py-2 mb-2">
                            <small><strong>{{ $product->name }}</strong></small><br>
                            <small>الكمية: {{ $product->quantity }} (الحد الأدنى: {{ $product->min_quantity }})</small>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">لا توجد منتجات منخفضة</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- آخر الفواتير -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">آخر الفواتير</h5>
                <a href="{{ route('cashier.invoices.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-2"></i>فاتورة جديدة
                </a>
            </div>
            <div class="card-body">
                @if($recentInvoices->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>رقم الفاتورة</th>
                                    <th>العميل</th>
                                    <th>المبلغ</th>
                                    <th>الكاشير</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentInvoices as $invoice)
                                    <tr>
                                        <td><strong>{{ $invoice->invoice_number }}</strong></td>
                                        <td>{{ $invoice->customer_name ?: 'غير محدد' }}</td>
                                        <td>{{ number_format($invoice->net_amount, 2) }} ر.س</td>
                                        <td>{{ $invoice->user->name }}</td>
                                        <td>{{ $invoice->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">لا توجد فواتير حديثة</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// رسم بياني للمبيعات
const salesData = @json($salesChart);
const ctx = document.getElementById('salesChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: salesData.map(item => item.date),
        datasets: [{
            label: 'المبيعات (ر.س)',
            data: salesData.map(item => item.total),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.1,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush