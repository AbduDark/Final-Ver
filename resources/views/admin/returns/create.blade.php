
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
                        <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
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
                               id="quantity" name="quantity" value="{{ old('quantity') }}" min="1" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">سبب الإرجاع</label>
                        <textarea class="form-control @error('reason') is-invalid @enderror" 
                                  id="reason" name="reason" rows="3" required>{{ old('reason') }}</textarea>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
