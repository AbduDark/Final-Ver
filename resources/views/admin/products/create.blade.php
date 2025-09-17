
@extends('layouts.app')

@section('title', 'إضافة منتج جديد')
@section('page-title', 'إضافة منتج جديد')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.products.index') }}" class="nav-link active">
            <i class="fas fa-box me-2"></i>
            المنتجات
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">إضافة منتج جديد</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.products.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">اسم المنتج</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code" class="form-label">كود المنتج</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       id="code" name="code" value="{{ old('code') }}" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">الفئة</label>
                                <div class="input-group">
                                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                        <option value="">اختر الفئة</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#categoryModal">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="purchase_price" class="form-label">سعر الشراء</label>
                                <input type="number" step="0.01" class="form-control @error('purchase_price') is-invalid @enderror" 
                                       id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}" required>
                                @error('purchase_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="sale_price" class="form-label">سعر البيع</label>
                                <input type="number" step="0.01" class="form-control @error('sale_price') is-invalid @enderror" 
                                       id="sale_price" name="sale_price" value="{{ old('sale_price') }}" required>
                                @error('sale_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">الكمية الحالية</label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                       id="quantity" name="quantity" value="{{ old('quantity') }}" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="min_quantity" class="form-label">الحد الأدنى للكمية</label>
                                <input type="number" class="form-control @error('min_quantity') is-invalid @enderror" 
                                       id="min_quantity" name="min_quantity" value="{{ old('min_quantity') }}" required>
                                @error('min_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
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

<!-- Modal إضافة فئة جديدة -->
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة فئة جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="categoryForm">
                    @csrf
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">اسم الفئة</label>
                        <input type="text" class="form-control" id="categoryName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="categoryDescription" class="form-label">الوصف</label>
                        <textarea class="form-control" id="categoryDescription" name="description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" onclick="saveCategory()">حفظ</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function saveCategory() {
    const formData = new FormData(document.getElementById('categoryForm'));
    
    fetch('{{ route("admin.products.create-category") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // إضافة الفئة الجديدة للقائمة
            const select = document.getElementById('category_id');
            const option = new Option(data.category.name, data.category.id, true, true);
            select.appendChild(option);
            
            // إغلاق المودال وإعادة تعيين النموذج
            bootstrap.Modal.getInstance(document.getElementById('categoryModal')).hide();
            document.getElementById('categoryForm').reset();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endpush
