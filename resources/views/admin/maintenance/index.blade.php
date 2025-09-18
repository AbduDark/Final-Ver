
@extends('layouts.app')

@section('title', 'إدارة طلبات الصيانة')
@section('page-title', 'إدارة طلبات الصيانة')

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
            طلبات الصيانة
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>إدارة طلبات الصيانة</h2>
    <a href="{{ route('admin.maintenance.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>إنشاء طلب صيانة جديد
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>رقم التذكرة</th>
                        <th>اسم الجهاز</th>
                        <th>نوع الجهاز</th>
                        <th>العميل</th>
                        <th>الأولوية</th>
                        <th>الفني المسؤول</th>
                        <th>الحالة</th>
                        <th>التكلفة</th>
                        <th>تاريخ الإنشاء</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request)
                    <tr>
                        <td>{{ $request->ticket_number }}</td>
                        <td>{{ $request->device_name }}</td>
                        <td>
                            <span class="badge bg-{{ $request->device_type === 'hardware' ? 'info' : 'secondary' }}">
                                {{ $request->device_type === 'hardware' ? 'عتاد' : 'برمجيات' }}
                            </span>
                        </td>
                        <td>{{ $request->customer_name }}</td>
                        <td>
                            <span class="badge bg-{{ $request->priority === 'high' ? 'danger' : ($request->priority === 'medium' ? 'warning' : 'success') }}">
                                {{ $request->priority === 'high' ? 'عالي' : ($request->priority === 'medium' ? 'متوسط' : 'منخفض') }}
                            </span>
                        </td>
                        <td>{{ $request->technician->name ?? 'غير محدد' }}</td>
                        <td>
                            <span class="badge bg-{{ $request->status === 'completed' ? 'success' : ($request->status === 'in_progress' ? 'warning' : 'secondary') }}">
                                {{ $request->status === 'completed' ? 'مكتمل' : ($request->status === 'in_progress' ? 'قيد العمل' : 'معلق') }}
                            </span>
                        </td>
                        <td>{{ $request->cost ? number_format($request->cost, 2) . ' ج.م' : 'غير محدد' }}</td>
                        <td>{{ $request->created_at->format('Y-m-d') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.maintenance.show', $request) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.maintenance.edit', $request) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $requests->links() }}
    </div>
</div>
@endsection
