
@extends('layouts.app')

@section('title', 'تعديل طلب الصيانة')
@section('page-title', 'تعديل طلب الصيانة')

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
                <h5 class="mb-0">تعديل طلب الصيانة: {{ $maintenance->ticket_number }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.maintenance.update', $maintenance) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="device_name" class="form-label">اسم الجهاز</label>
                                <input type="text" class="form-control" 
                                       id="device_name" name="device_name" value="{{ $maintenance->device_name }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="device_type" class="form-label">نوع الجهاز</label>
                                <input type="text" class="form-control" 
                                       id="device_type" value="{{ $maintenance->device_type === 'hardware' ? 'هاردوير' : 'سوفتوير' }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="problem_description" class="form-label">وصف المشكلة</label>
                        <textarea class="form-control" 
                                  id="problem_description" rows="3" readonly>{{ $maintenance->problem_description }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="priority" class="form-label">الأولوية</label>
                                <input type="text" class="form-control" 
                                       value="{{ $maintenance->priority === 'high' ? 'عالية' : ($maintenance->priority === 'medium' ? 'متوسطة' : 'منخفضة') }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="customer_name" class="form-label">اسم العميل</label>
                                <input type="text" class="form-control" 
                                       value="{{ $maintenance->customer_name }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="customer_phone" class="form-label">رقم الهاتف</label>
                                <input type="text" class="form-control" 
                                       value="{{ $maintenance->customer_phone }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">الحالة</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="pending" {{ $maintenance->status === 'pending' ? 'selected' : '' }}>معلق</option>
                                    <option value="in_progress" {{ $maintenance->status === 'in_progress' ? 'selected' : '' }}>قيد العمل</option>
                                    <option value="completed" {{ $maintenance->status === 'completed' ? 'selected' : '' }}>مكتمل</option>
                                    <option value="cancelled" {{ $maintenance->status === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cost" class="form-label">التكلفة (ريال)</label>
                                <input type="number" step="0.01" class="form-control @error('cost') is-invalid @enderror" 
                                       id="cost" name="cost" value="{{ old('cost', $maintenance->cost) }}">
                                @error('cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="technician_id" class="form-label">الفني المختص</label>
                        <select class="form-select @error('technician_id') is-invalid @enderror" id="technician_id" name="technician_id">
                            <option value="">اختر الفني</option>
                            @foreach($technicians as $technician)
                                <option value="{{ $technician->id }}" {{ old('technician_id', $maintenance->technician_id) == $technician->id ? 'selected' : '' }}>
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
                        <div>
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-save me-2"></i>حفظ التغييرات
                            </button>
                            <form action="{{ route('admin.maintenance.destroy', $maintenance) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" 
                                        onclick="return confirm('هل أنت متأكد من حذف طلب الصيانة هذا؟')">
                                    <i class="fas fa-trash me-2"></i>حذف
                                </button>
                            </form>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
