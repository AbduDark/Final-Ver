
@extends('layouts.app')

@section('title', 'إضافة باقة جديدة')
@section('page-title', 'إضافة باقة جديدة')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.packages.index') }}" class="nav-link active">
            <i class="fas fa-box-open me-2"></i>
            الباقات
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">إضافة باقة جديدة</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.packages.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">اسم الباقة</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="network" class="form-label">الشبكة</label>
                        <select class="form-select @error('network') is-invalid @enderror" id="network" name="network" required>
                            <option value="">اختر الشبكة</option>
                            <option value="vodafone" {{ old('network') === 'vodafone' ? 'selected' : '' }}>فودافون</option>
                            <option value="orange" {{ old('network') === 'orange' ? 'selected' : '' }}>أورانج</option>
                            <option value="etisalat" {{ old('network') === 'etisalat' ? 'selected' : '' }}>اتصالات</option>
                            <option value="we" {{ old('network') === 'we' ? 'selected' : '' }}>المصرية للاتصالات</option>
                        </select>
                        @error('network')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="purchase_price" class="form-label">سعر الشراء</label>
                                <input type="number" step="0.01" class="form-control @error('purchase_price') is-invalid @enderror" 
                                       id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}" required>
                                @error('purchase_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
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

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">
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
