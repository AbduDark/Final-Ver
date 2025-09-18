
@extends('layouts.app')

@section('title', 'إضافة مستخدم جديد')
@section('page-title', 'إضافة مستخدم جديد للمتجر: ' . $store->name)

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('superadmin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('superadmin.stores.index') }}" class="nav-link active">
            <i class="fas fa-store me-2"></i>
            إدارة المتاجر
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('superadmin.users.index') }}" class="nav-link">
            <i class="fas fa-users me-2"></i>
            إدارة المستخدمين
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>إضافة مستخدم جديد</h2>
    <a href="{{ route('superadmin.stores.users', $store) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>العودة
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-user-plus me-2"></i>بيانات المستخدم الجديد - {{ $store->name }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('superadmin.stores.users.store', $store) }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">الاسم <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="type" class="form-label">نوع المستخدم <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">اختر النوع</option>
                            <option value="admin" {{ old('type') == 'admin' ? 'selected' : '' }}>مدير</option>
                            <option value="cashier" {{ old('type') == 'cashier' ? 'selected' : '' }}>كاشير</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">المتجر</label>
                        <input type="text" class="form-control" value="{{ $store->name }}" disabled>
                        <input type="hidden" name="store_id" value="{{ $store->id }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password" class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('superadmin.stores.users', $store) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right me-2"></i>رجوع
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>حفظ
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
