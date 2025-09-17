@extends('layouts.app')

@section('title', 'إنشاء فاتورة جديدة')
@section('page-title', 'إنشاء فاتورة جديدة')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('cashier.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('cashier.invoices.create') }}" class="nav-link active">
            <i class="fas fa-receipt me-2"></i>
            فاتورة جديدة
        </a>
    </li>
</ul>
@endsection

@section('content')
<form method="POST" action="{{ route('cashier.invoices.store') }}" id="invoiceForm">
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
                </div>
            </div>

            <!-- ملخص الفاتورة -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">ملخص الفاتورة</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>المجموع الفرعي:</span>
                        <span id="subtotal">0.00 ر.س</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>الخصم:</span>
                        <span id="discountAmount">0.00 ر.س</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>الضريبة (15%):</span>
                        <span id="taxAmount">0.00 ر.س</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>المجموع النهائي:</strong>
                        <strong id="total">0.00 ر.س</strong>
                    </div>
                    
                    <div class="mt-3">
                        <label for="discount" class="form-label">خصم إضافي</label>
                        <input type="number" class="form-control" id="discount" name="discount" 
                               min="0" step="0.01" value="0" onchange="updateTotals()">
                    </div>
                    
                    <div class="mt-3 d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>حفظ الفاتورة
                        </button>
                        <button type="button" class="btn btn-success" onclick="saveAndPrint()">
                            <i class="fas fa-print me-2"></i>حفظ وطباعة
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- المنتجات والبحث -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">البحث عن المنتجات</h5>
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
                        <div id="emptyMessage" class="text-center text-muted py-4">
                            لا توجد منتجات في الفاتورة
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
let items = [];
let itemCounter = 0;

// البحث عن المنتجات
$('#productSearch').on('keyup', function() {
    const query = $(this).val();
    if (query.length >= 2) {
        $.get(`{{ route('cashier.products.search') }}`, { q: query }, function(products) {
            let html = '';
            products.forEach(function(product) {
                html += `
                    <div class="border p-2 mb-2 rounded product-item" style="cursor: pointer;" 
                         onclick="addProduct(${product.id}, '${product.name}', ${product.sale_price}, ${product.quantity})">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>${product.name}</strong><br>
                                <small>الكود: ${product.code} | الفئة: ${product.category.name}</small>
                            </div>
                            <div class="text-end">
                                <strong>${product.sale_price} ر.س</strong><br>
                                <small>المتوفر: ${product.quantity}</small>
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
function addProduct(productId, productName, price, availableQuantity) {
    // التحقق من وجود المنتج مسبقاً
    const existingItem = items.find(item => item.product_id == productId);
    
    if (existingItem) {
        if (existingItem.quantity < availableQuantity) {
            existingItem.quantity++;
            updateItemRow(existingItem.id);
        } else {
            alert('تم الوصول للحد الأقصى المتاح من هذا المنتج');
        }
    } else {
        const item = {
            id: ++itemCounter,
            product_id: productId,
            product_name: productName,
            price: price,
            quantity: 1,
            available: availableQuantity
        };
        items.push(item);
        addItemRow(item);
    }
    
    updateTotals();
    $('#productSearch').val('');
    $('#searchResults').html('');
}

// إضافة صف للجدول
function addItemRow(item) {
    const html = `
        <tr id="item-${item.id}">
            <td>
                ${item.product_name}
                <input type="hidden" name="items[${item.id}][product_id]" value="${item.product_id}">
            </td>
            <td>${item.price.toFixed(2)} ر.س</td>
            <td>
                <input type="number" class="form-control form-control-sm" 
                       name="items[${item.id}][quantity]" value="${item.quantity}" 
                       min="1" max="${item.available}" 
                       onchange="updateQuantity(${item.id}, this.value)">
            </td>
            <td id="total-${item.id}">${(item.price * item.quantity).toFixed(2)} ر.س</td>
            <td>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${item.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;
    $('#itemsContainer').append(html);
    $('#emptyMessage').hide();
}

// تحديث الكمية
function updateQuantity(itemId, newQuantity) {
    const item = items.find(i => i.id == itemId);
    if (item) {
        if (newQuantity <= item.available && newQuantity > 0) {
            item.quantity = parseInt(newQuantity);
            updateItemRow(itemId);
            updateTotals();
        } else {
            alert('الكمية غير صحيحة');
            $(`input[name="items[${itemId}][quantity]"]`).val(item.quantity);
        }
    }
}

// تحديث صف العنصر
function updateItemRow(itemId) {
    const item = items.find(i => i.id == itemId);
    const total = item.price * item.quantity;
    $(`#total-${itemId}`).text(`${total.toFixed(2)} ر.س`);
    $(`input[name="items[${itemId}][quantity]"]`).val(item.quantity);
}

// حذف عنصر
function removeItem(itemId) {
    items = items.filter(i => i.id != itemId);
    $(`#item-${itemId}`).remove();
    
    if (items.length === 0) {
        $('#emptyMessage').show();
    }
    
    updateTotals();
}

// تحديث المجاميع
function updateTotals() {
    let subtotal = items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    let discount = parseFloat($('#discount').val()) || 0;
    let tax = (subtotal - discount) * 0.15;
    let total = subtotal - discount + tax;
    
    $('#subtotal').text(`${subtotal.toFixed(2)} ر.س`);
    $('#discountAmount').text(`${discount.toFixed(2)} ر.س`);
    $('#taxAmount').text(`${tax.toFixed(2)} ر.س`);
    $('#total').text(`${total.toFixed(2)} ر.س`);
}

// حفظ وطباعة
function saveAndPrint() {
    $('#invoiceForm').append('<input type="hidden" name="print" value="1">');
    $('#invoiceForm').submit();
}
</script>
@endpush