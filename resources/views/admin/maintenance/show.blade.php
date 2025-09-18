
@extends('layouts.app')

@section('title', 'تفاصيل طلب الصيانة')
@section('page-title', 'تفاصيل طلب الصيانة رقم: ' . $maintenance->ticket_number)

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
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>تفاصيل طلب الصيانة رقم: {{ $maintenance->ticket_number }}</h2>
    <div>
        <a href="{{ route('admin.maintenance.edit', $maintenance) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>تعديل
        </a>
        <a href="{{ route('admin.maintenance.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>العودة
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>معلومات الطلب</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>رقم التذكرة:</strong></td>
                        <td>{{ $maintenance->ticket_number }}</td>
                    </tr>
                    <tr>
                        <td><strong>اسم الجهاز:</strong></td>
                        <td>{{ $maintenance->device_name }}</td>
                    </tr>
                    <tr>
                        <td><strong>نوع الجهاز:</strong></td>
                        <td>
                            <span class="badge bg-{{ $maintenance->device_type === 'hardware' ? 'primary' : 'info' }}">
                                {{ $maintenance->device_type === 'hardware' ? 'أجهزة' : 'برمجيات' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>الأولوية:</strong></td>
                        <td>
                            @switch($maintenance->priority)
                                @case('high')
                                    <span class="badge bg-danger">عالية</span>
                                    @break
                                @case('medium')
                                    <span class="badge bg-warning">متوسطة</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">منخفضة</span>
                            @endswitch
                        </td>
                    </tr>
                    <tr>
                        <td><strong>الحالة:</strong></td>
                        <td>
                            @switch($maintenance->status)
                                @case('completed')
                                    <span class="badge bg-success">مكتمل</span>
                                    @break
                                @case('in_progress')
                                    <span class="badge bg-warning">قيد التنفيذ</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-danger">ملغي</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">معلق</span>
                            @endswitch
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>معلومات العميل</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>اسم العميل:</strong></td>
                        <td>{{ $maintenance->customer_name }}</td>
                    </tr>
                    <tr>
                        <td><strong>رقم الهاتف:</strong></td>
                        <td>{{ $maintenance->customer_phone }}</td>
                    </tr>
                    <tr>
                        <td><strong>الفني المخصص:</strong></td>
                        <td>{{ $maintenance->technician->name ?? 'غير محدد' }}</td>
                    </tr>
                    <tr>
                        <td><strong>التكلفة:</strong></td>
                        <td>{{ $maintenance->cost ? number_format($maintenance->cost, 2) . ' ج.م' : 'غير محددة' }}</td>
                    </tr>
                    <tr>
                        <td><strong>تاريخ الإنشاء:</strong></td>
                        <td>{{ $maintenance->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5>وصف المشكلة</h5>
    </div>
    <div class="card-body">
        <p>{{ $maintenance->problem_description }}</p>
    </div>
</div>
@endsection
