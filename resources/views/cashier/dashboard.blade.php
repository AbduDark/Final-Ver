@extends('layouts.app')

@section('title', 'واجهة الكاشير')
@section('page-title', 'واجهة الكاشير - ' . $store->name)

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('cashier.dashboard') }}" class="nav-link active">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('cashier.invoices.create') }}" class="nav-link">
            <i class="fas fa-receipt me-2"></i>
            فاتورة جديدة
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('cashier.invoices.index') }}" class="nav-link">
            <i class="fas fa-file-invoice me-2"></i>
            الفواتير
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('cashier.maintenance.index') }}" class="nav-link">
            <i class="fas fa-tools me-2"></i>
            طلبات الصيانة
        </a>
    </li>
</ul>
@endsection

@section('content')
<!-- الإحصائيات السريعة -->
<div class="row mb-4">
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card stats-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">فواتير اليوم</h6>
                        <h3 class="mb-0">{{ $todayInvoices }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-receipt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card stats-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">مبيعات اليوم</h6>
                        <h3 class="mb-0">{{ number_format($todayRevenue, 2) }} ر.س</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card stats-card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">تحويلات معلقة</h6>
                        <h3 class="mb-0">{{ $pendingTransfers }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-exchange-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- وصول سريع -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">وصول سريع</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('cashier.invoices.create') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-plus fa-2x mb-2 d-block"></i>
                            إنشاء فاتورة جديدة
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <button class="btn btn-info btn-lg w-100" onclick="searchProduct()">
                            <i class="fas fa-search fa-2x mb-2 d-block"></i>
                            البحث عن منتج
                        </button>
                    </div>
                    <div class="col-md-4 mb-3">
                        <button class="btn btn-success btn-lg w-100" onclick="printLastInvoice()">
                            <i class="fas fa-print fa-2x mb-2 d-block"></i>
                            طباعة آخر فاتورة
                        </button>
                    </div>
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
                <h5 class="card-title mb-0">آخر الفواتير</h5>
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
                                    <th>التاريخ</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentInvoices as $invoice)
                                    <tr>
                                        <td><strong>{{ $invoice->invoice_number }}</strong></td>
                                        <td>{{ $invoice->customer_name ?: 'غير محدد' }}</td>
                                        <td>{{ number_format($invoice->net_amount, 2) }} ر.س</td>
                                        <td>{{ $invoice->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-success" onclick="printInvoice({{ $invoice->id }})">
                                                <i class="fas fa-print"></i> طباعة
                                            </button>
                                        </td>
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

<!-- Modal للبحث عن منتج -->
<div class="modal fade" id="searchModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">البحث عن منتج</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control" id="productSearch" placeholder="ابحث عن منتج...">
                <div id="searchResults" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function searchProduct() {
    $('#searchModal').modal('show');
    $('#productSearch').focus();
}

function printLastInvoice() {
    @if($recentInvoices->count() > 0)
        printInvoice({{ $recentInvoices->first()->id }});
    @else
        alert('لا توجد فواتير للطباعة');
    @endif
}

function printInvoice(invoiceId) {
    // إنشاء نافذة طباعة للفاتورة
    const printWindow = window.open(`/cashier/invoices/${invoiceId}/print`, '_blank', 'width=800,height=600');
    printWindow.onload = function() {
        printWindow.print();
    };
}

// البحث عن المنتجات
$('#productSearch').on('keyup', function() {
    const query = $(this).val();
    if (query.length >= 2) {
        $.get(`{{ route('cashier.products.search') }}`, { q: query }, function(products) {
            let html = '';
            products.forEach(function(product) {
                html += `
                    <div class="border p-2 mb-2 rounded">
                        <strong>${product.name}</strong> - ${product.code}<br>
                        <small>الفئة: ${product.category.name} | الكمية: ${product.quantity} | السعر: ${product.sale_price} ر.س</small>
                    </div>
                `;
            });
            $('#searchResults').html(html);
        });
    } else {
        $('#searchResults').html('');
    }
});
</script>
@endpush