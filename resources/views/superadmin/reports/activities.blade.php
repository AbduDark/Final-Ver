
@extends('layouts.app')

@section('title', 'سجل الأنشطة - السوبر أدمن')
@section('page-title', 'سجل الأنشطة لجميع المتاجر')

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
        <a href="{{ route('superadmin.reports.index') }}" class="nav-link active">
            <i class="fas fa-chart-bar me-2"></i>
            التقارير والإحصائيات
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>سجل الأنشطة لجميع المتاجر</h2>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>آخر 100 نشاط في جميع المتاجر</h5>
    </div>
    <div class="card-body">
        @if($activities->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>النوع</th>
                        <th>التفاصيل</th>
                        <th>المستخدم</th>
                        <th>المتجر</th>
                        <th>التاريخ والوقت</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activities as $activity)
                    <tr>
                        <td>
                            @if($activity['type'] == 'sale')
                            <span class="badge bg-success">
                                <i class="fas fa-shopping-cart me-1"></i>مبيعات
                            </span>
                            @endif
                        </td>
                        <td>{{ $activity['description'] }}</td>
                        <td>{{ $activity['user'] }}</td>
                        <td>{{ $activity['store'] }}</td>
                        <td>{{ $activity['created_at']->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-4">
            <i class="fas fa-history fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">لا توجد أنشطة مسجلة</h5>
        </div>
        @endif
    </div>
</div>
@endsection
