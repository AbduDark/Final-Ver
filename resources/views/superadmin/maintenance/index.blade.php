
@extends('layouts.app')

@section('title', 'طلبات الصيانة - السوبر أدمن')
@section('page-title', 'طلبات الصيانة لجميع المتاجر')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('superadmin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('superadmin.stores.index') }}" class="nav-link">
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
    <li class="nav-item">
        <a href="{{ route('superadmin.treasury.index') }}" class="nav-link">
            <i class="fas fa-coins me-2"></i>
            إدارة الخزائن
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('superadmin.maintenance.index') }}" class="nav-link active">
            <i class="fas fa-tools me-2"></i>
            طلبات الصيانة
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('superadmin.packages.index') }}" class="nav-link">
            <i class="fas fa-cube me-2"></i>
            إدارة الباقات
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('superadmin.reports.index') }}" class="nav-link">
            <i class="fas fa-chart-bar me-2"></i>
            التقارير والإحصائيات
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>طلبات الصيانة لجميع المتاجر</h2>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>جميع طلبات الصيانة</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>رقم التذكرة</th>
                        <th>اسم الجهاز</th>
                        <th>العميل</th>
                        <th>المتجر</th>
                        <th>الفني</th>
                        <th>الحالة</th>
                        <th>التكلفة</th>
                        <th>تاريخ الإنشاء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($maintenanceRequests as $request)
                    <tr>
                        <td>{{ $request->ticket_number }}</td>
                        <td>{{ $request->device_name }}</td>
                        <td>{{ $request->customer_name }}</td>
                        <td>{{ $request->store->name }}</td>
                        <td>{{ $request->technician->name ?? 'غير محدد' }}</td>
                        <td>
                            <span class="badge bg-{{ $request->status === 'completed' ? 'success' : ($request->status === 'in_progress' ? 'warning' : 'secondary') }}">
                                {{ $request->status === 'completed' ? 'مكتمل' : ($request->status === 'in_progress' ? 'قيد العمل' : 'معلق') }}
                            </span>
                        </td>
                        <td>{{ $request->cost ? number_format($request->cost, 2) . ' ج.م' : 'غير محدد' }}</td>
                        <td>{{ $request->created_at->format('Y-m-d') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $maintenanceRequests->links() }}
    </div>
</div>
@endsection
