
@extends('layouts.app')

@section('title', 'إضافة تحويل جديد')
@section('page-title', 'إضافة تحويل جديد')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.transfers.index') }}" class="nav-link active">
            <i class="fas fa-exchange-alt me-2"></i>
            التحويلات
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">إضافة تحويل جديد</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.transfers.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="transfer_type" class="form-label">نوع التحويل</label>
                        <select class="form-select @error('transfer_type') is-invalid @enderror" id="transfer_type" name="transfer_type" required>
                            <option value="">اختر نوع التحويل</option>
                            <option value="instant_payment" {{ old('transfer_type') === 'instant_payment' ? 'selected' : '' }}>دفع فوري</option>
                            <option value="recharge_cards" {{ old('transfer_type') === 'recharge_cards' ? 'selected' : '' }}>كروت شحن</option>
                            <option value="vodafone_cash" {{ old('transfer_type') === 'vodafone_cash' ? 'selected' : '' }}>فودافون كاش</option>
                            <option value="etisalat_cash" {{ old('transfer_type') === 'etisalat_cash' ? 'selected' : '' }}>اتصالات كاش</option>
                            <option value="orange_cash" {{ old('transfer_type') === 'orange_cash' ? 'selected' : '' }}>أورانج كاش</option>
                        </select>
                        @error('transfer_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">المبلغ</label>
                        <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" 
                               id="amount" name="amount" value="{{ old('amount') }}" required>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="customer_name" class="form-label">اسم العميل</label>
                        <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                               id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="customer_phone" class="form-label">رقم الهاتف</label>
                        <input type="text" class="form-control @error('customer_phone') is-invalid @enderror" 
                               id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required>
                        @error('customer_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.transfers.index') }}" class="btn btn-secondary">
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
