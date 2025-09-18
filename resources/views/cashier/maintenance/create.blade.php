
@extends('layouts.app')

@section('title', 'إنشاء طلب صيانة جديد')
@section('page-title', 'إنشاء طلب صيانة جديد')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('cashier.dashboard') }}" class="nav-link">
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
        <a href="{{ route('cashier.maintenance.index') }}" class="nav-link active">
            <i class="fas fa-tools me-2"></i>
            طلبات الصيانة
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>إنشاء طلب صيانة جديد</h2>
    <a href="{{ route('cashier.maintenance.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>العودة
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('cashier.maintenance.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="device_name" class="form-label">اسم الجهاز <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('device_name') is-invalid @enderror" 
                               id="device_name" name="device_name" value="{{ old('device_name') }}" required>
                        @error('device_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="device_type" class="form-label">نوع الجهاز <span class="text-danger">*</span></label>
                        <select class="form-select @error('device_type') is-invalid @enderror" 
                                id="device_type" name="device_type" required>
                            <option value="">اختر نوع الجهاز</option>
                            <option value="hardware" {{ old('device_type') == 'hardware' ? 'selected' : '' }}>عتاد</option>
                            <option value="software" {{ old('device_type') == 'software' ? 'selected' : '' }}>برمجيات</option>
                        </select>
                        @error('device_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="customer_name" class="form-label">اسم العميل <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                               id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="customer_phone" class="form-label">هاتف العميل <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('customer_phone') is-invalid @enderror" 
                               id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required>
                        @error('customer_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="priority" class="form-label">الأولوية <span class="text-danger">*</span></label>
                        <select class="form-select @error('priority') is-invalid @enderror" 
                                id="priority" name="priority" required>
                            <option value="">اختر الأولوية</option>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>منخفض</option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>متوسط</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>عالي</option>
                        </select>
                        @error('priority')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="technician_id" class="form-label">الفني المسؤول</label>
                        <select class="form-select @error('technician_id') is-invalid @enderror" 
                                id="technician_id" name="technician_id">
                            <option value="">اختر الفني المسؤول</option>
                            @foreach($technicians as $technician)
                                <option value="{{ $technician->id }}" 
                                        {{ old('technician_id') == $technician->id ? 'selected' : '' }}>
                                    {{ $technician->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('technician_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="problem_description" class="form-label">وصف المشكلة <span class="text-danger">*</span></label>
                <textarea class="form-control @error('problem_description') is-invalid @enderror" 
                          id="problem_description" name="problem_description" rows="4" required>{{ old('problem_description') }}</textarea>
                @error('problem_description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>إنشاء الطلب
                </button>
                <a href="{{ route('cashier.maintenance.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
