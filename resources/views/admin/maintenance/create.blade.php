
@extends('layouts.app')

@section('title', 'إضافة طلب صيانة')
@section('page-title', 'إضافة طلب صيانة')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.maintenance.index') }}" class="nav-link active">
            <i class="fas fa-tools me-2"></i>
            الصيانة
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">إضافة طلب صيانة جديد</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.maintenance.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="device_name" class="form-label">اسم الجهاز</label>
                                <input type="text" class="form-control @error('device_name') is-invalid @enderror" 
                                       id="device_name" name="device_name" value="{{ old('device_name') }}" required>
                                @error('device_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="device_type" class="form-label">نوع الجهاز</label>
                                <select class="form-select @error('device_type') is-invalid @enderror" id="device_type" name="device_type" required>
                                    <option value="">اختر نوع الجهاز</option>
                                    <option value="hardware" {{ old('device_type') === 'hardware' ? 'selected' : '' }}>هاردوير</option>
                                    <option value="software" {{ old('device_type') === 'software' ? 'selected' : '' }}>سوفتوير</option>
                                </select>
                                @error('device_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="problem_description" class="form-label">وصف المشكلة</label>
                        <textarea class="form-control @error('problem_description') is-invalid @enderror" 
                                  id="problem_description" name="problem_description" rows="4" required>{{ old('problem_description') }}</textarea>
                        @error('problem_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="priority" class="form-label">الأولوية</label>
                                <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                    <option value="">اختر الأولوية</option>
                                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>منخفضة</option>
                                    <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>متوسطة</option>
                                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>عالية</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="customer_name" class="form-label">اسم العميل</label>
                                <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                       id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="customer_phone" class="form-label">رقم الهاتف</label>
                                <input type="text" class="form-control @error('customer_phone') is-invalid @enderror" 
                                       id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required>
                                @error('customer_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="technician_id" class="form-label">الفني المختص (اختياري)</label>
                        <select class="form-select @error('technician_id') is-invalid @enderror" id="technician_id" name="technician_id">
                            <option value="">اختر الفني</option>
                            @foreach($technicians as $technician)
                                <option value="{{ $technician->id }}" {{ old('technician_id') == $technician->id ? 'selected' : '' }}>
                                    {{ $technician->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('technician_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.maintenance.index') }}" class="btn btn-secondary">
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
