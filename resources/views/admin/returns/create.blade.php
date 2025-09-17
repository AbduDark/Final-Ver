@extends('layouts.app')

@section('title', 'إضافة مرتجع')
@section('page-title', 'إضافة مرتجع')

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
            المرتجعات
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">إضافة مرتجع جديد</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.returns.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="invoice_number" class="form-label">رقم الفاتورة</label>
                        <input type="text" class="form-control @error('invoice_number') is-invalid @enderror"
                               id="invoice_number" name="invoice_number" value="{{ old('invoice_number') }}" required>
                        @error('invoice_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="product_id" class="form-label">المنتج</label>
                        <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required disabled>
                            <option value="">اختر المنتج</option>
                            <!-- سيتم ملؤها بـ JavaScript بناءً على رقم الفاتورة -->
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label">الكمية المرتجعة</label>
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                               id="quantity" name="quantity" value="{{ old('quantity') }}" min="1" required disabled>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <span class="form-text">الكمية القصوى: <span id="max-quantity">0</span></span>
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">سبب الإرجاع</label>
                        <textarea class="form-control @error('reason') is-invalid @enderror"
                                  id="reason" name="reason" rows="3" required>{{ old('reason') }}</textarea>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="expected_refund" class="form-label">المبلغ المتوقع إرجاعه</label>
                        <span id="expected-refund" class="form-control-plaintext">0.00 ريال</span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.returns.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right me-2"></i>رجوع
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>حفظ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('invoice_number').addEventListener('blur', function() {
    const invoiceNumber = this.value;
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');

    if (invoiceNumber) {
        fetch(`/admin/invoices/items/${invoiceNumber}`)
            .then(response => response.json())
            .then(data => {
                productSelect.innerHTML = '<option value="">اختر المنتج</option>';

                if (data.success && data.items.length > 0) {
                    data.items.forEach(item => {
                        const option = new Option(
                            `${item.product.name} - كمية: ${item.quantity}`,
                            item.product.id
                        );
                        option.dataset.quantity = item.quantity;
                        option.dataset.unitPrice = item.unit_price;
                        productSelect.appendChild(option);
                    });
                    productSelect.disabled = false;
                } else {
                    productSelect.innerHTML = '<option value="">لا توجد منتجات في هذه الفاتورة</option>';
                    productSelect.disabled = true;
                    quantityInput.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                productSelect.innerHTML = '<option value="">حدث خطأ في تحميل المنتجات</option>';
                productSelect.disabled = true;
                quantityInput.disabled = true;
            });
    } else {
        productSelect.innerHTML = '<option value="">أدخل رقم الفاتورة أولاً</option>';
        productSelect.disabled = true;
        quantityInput.disabled = true;
    }
});

document.getElementById('product_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const quantityInput = document.getElementById('quantity');
    const maxQuantitySpan = document.getElementById('max-quantity');

    if (selectedOption.value) {
        const maxQuantity = parseInt(selectedOption.dataset.quantity);
        quantityInput.max = maxQuantity;
        quantityInput.disabled = false;
        maxQuantitySpan.textContent = maxQuantity;
        updateExpectedRefund();
    } else {
        quantityInput.disabled = true;
        quantityInput.max = 0;
        maxQuantitySpan.textContent = '0';
        document.getElementById('expected-refund').textContent = '0.00 ريال';
    }
});

document.getElementById('quantity').addEventListener('input', updateExpectedRefund);

function updateExpectedRefund() {
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const refundDiv = document.getElementById('expected-refund');

    const selectedOption = productSelect.options[productSelect.selectedIndex];

    if (selectedOption.value && quantityInput.value) {
        const unitPrice = parseFloat(selectedOption.dataset.unitPrice);
        const quantity = parseInt(quantityInput.value);
        const refund = unitPrice * quantity;

        refundDiv.textContent = `${refund.toFixed(2)} ريال`;
    } else {
        refundDiv.textContent = '0.00 ريال';
    }
}
</script>
@endpush