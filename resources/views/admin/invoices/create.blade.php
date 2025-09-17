
@extends('layouts.app')

@section('title', 'إنشاء فاتورة جديدة')
@section('page-title', 'إنشاء فاتورة جديدة')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.invoices.index') }}" class="nav-link">
            <i class="fas fa-receipt me-2"></i>
            إدارة الفواتير
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.invoices.create') }}" class="nav-link active">
            <i class="fas fa-plus me-2"></i>
            فاتورة جديدة
        </a>
    </li>
</ul>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.invoices.store') }}" id="invoiceForm">
    @csrf
    <div class="row">
        <!-- معلومات العميل -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">معلومات العميل</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="customer_name" class="form-label">اسم العميل (اختياري)</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name">
                    </div>
                    <div class="mb-3">
                        <label for="customer_phone" class="form-label">رقم الهاتف (اختياري)</label>
                        <input type="text" class="form-control" id="customer_phone" name="customer_phone">
                    </div>
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">طريقة الدفع <span class="text-danger">*</span></label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="cash">نقداً</option>
                            <option value="card">بطاقة ائتمانية</option>
                            <option value="transfer">تحويل</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="discount" class="form-label">الخصم</label>
                        <input type="number" class="form-control" id="discount" name="discount" step="0.01" min="0" value="0">
                    </div>
                </div>
            </div>

            <!-- ملخص الفاتورة -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">ملخص الفاتورة</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">المجموع:</div>
                        <div class="col-6 text-end" id="totalAmount">0.00 ج.م</div>
                    </div>
                    <div class="row">
                        <div class="col-6">الخصم:</div>
                        <div class="col-6 text-end" id="discountAmount">0.00 ج.م</div>
                    </div>
                    <div class="row">
                        <div class="col-6">الضريبة:</div>
                        <div class="col-6 text-end" id="taxAmount">0.00 ج.م</div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6"><strong>الصافي:</strong></div>
                        <div class="col-6 text-end"><strong id="netAmount">0.00 ج.م</strong></div>
                    </div>
                    <button type="submit" class="btn btn-success w-100 mt-3">
                        <i class="fas fa-save me-2"></i>حفظ الفاتورة
                    </button>
                </div>
            </div>
        </div>

        <!-- المنتجات والفاتورة -->
        <div class="col-md-8">
            <!-- البحث عن المنتجات -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">إضافة منتج</h5>
                </div>
                <div class="card-body">
                    <input type="text" class="form-control" id="productSearch" 
                           placeholder="ابحث عن منتج بالاسم أو الكود...">
                    <div id="searchResults" class="mt-3"></div>
                </div>
            </div>

            <!-- عناصر الفاتورة -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">عناصر الفاتورة</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="invoiceItems">
                            <thead>
                                <tr>
                                    <th>المنتج</th>
                                    <th>السعر</th>
                                    <th>الكمية</th>
                                    <th>المجموع</th>
                                    <th>حذف</th>
                                </tr>
                            </thead>
                            <tbody id="itemsContainer">
                                <!-- سيتم إضافة العناصر هنا -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
let invoiceItems = [];

// البحث عن المنتجات
$('#productSearch').on('keyup', function() {
    const query = $(this).val();
    if (query.length >= 2) {
        $.get(`{{ route('admin.products.search') }}`, { q: query }, function(products) {
            let html = '';
            products.forEach(function(product) {
                html += `
                    <div class="border p-2 mb-2 rounded product-item" data-product='${JSON.stringify(product)}'>
                        <div class="row align-items-center">
                            <div class="col-8">
                                <strong>${product.name}</strong><br>
                                <small class="text-muted">الكود: ${product.code} | المخزون: ${product.quantity}</small>
                            </div>
                            <div class="col-4 text-end">
                                <strong>${product.sale_price} ج.م</strong><br>
                                <button type="button" class="btn btn-sm btn-primary add-product">إضافة</button>
                            </div>
                        </div>
                    </div>
                `;
            });
            $('#searchResults').html(html);
        });
    } else {
        $('#searchResults').html('');
    }
});

// إضافة منتج للفاتورة
$(document).on('click', '.add-product', function() {
    const productData = $(this).closest('.product-item').data('product');
    addProductToInvoice(productData);
});

function addProductToInvoice(product) {
    // التحقق من وجود المنتج مسبقاً
    let existingItem = invoiceItems.find(item => item.product_id === product.id);
    
    if (existingItem) {
        existingItem.quantity++;
    } else {
        invoiceItems.push({
            product_id: product.id,
            product_name: product.name,
            unit_price: product.sale_price,
            quantity: 1,
            available_quantity: product.quantity
        });
    }
    
    updateInvoiceDisplay();
}

function updateInvoiceDisplay() {
    let html = '';
    let totalAmount = 0;
    
    invoiceItems.forEach(function(item, index) {
        const itemTotal = item.unit_price * item.quantity;
        totalAmount += itemTotal;
        
        html += `
            <tr>
                <td>
                    ${item.product_name}
                    <input type="hidden" name="items[${index}][product_id]" value="${item.product_id}">
                </td>
                <td>${item.unit_price} ج.م</td>
                <td>
                    <input type="number" class="form-control quantity-input" data-index="${index}" 
                           value="${item.quantity}" min="1" max="${item.available_quantity}">
                    <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                </td>
                <td>${itemTotal.toFixed(2)} ج.م</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger remove-item" data-index="${index}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    $('#itemsContainer').html(html);
    
    // تحديث الملخص
    const discount = parseFloat($('#discount').val() || 0);
    const tax = totalAmount * 0.15;
    const netAmount = totalAmount - discount + tax;
    
    $('#totalAmount').text(totalAmount.toFixed(2) + ' ج.م');
    $('#discountAmount').text(discount.toFixed(2) + ' ج.م');
    $('#taxAmount').text(tax.toFixed(2) + ' ج.م');
    $('#netAmount').text(netAmount.toFixed(2) + ' ج.م');
}

// تحديث الكمية
$(document).on('change', '.quantity-input', function() {
    const index = $(this).data('index');
    const newQuantity = parseInt($(this).val());
    invoiceItems[index].quantity = newQuantity;
    $(`input[name="items[${index}][quantity]"]`).val(newQuantity);
    updateInvoiceDisplay();
});

// حذف عنصر
$(document).on('click', '.remove-item', function() {
    const index = $(this).data('index');
    invoiceItems.splice(index, 1);
    updateInvoiceDisplay();
});

// تحديث الخصم
$('#discount').on('change', function() {
    updateInvoiceDisplay();
});

// التحقق من صحة النموذج
$('#invoiceForm').on('submit', function(e) {
    if (invoiceItems.length === 0) {
        e.preventDefault();
        alert('يجب إضافة منتج واحد على الأقل');
        return false;
    }
});
</script>
@endpush
@endsection
