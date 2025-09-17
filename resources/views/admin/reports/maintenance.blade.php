
@extends('layouts.app')

@section('title', 'تقرير الصيانة')
@section('page-title', 'تقرير الصيانة')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.reports.index') }}" class="nav-link active">
            <i class="fas fa-chart-bar me-2"></i>
            التقارير
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>تقرير الصيانة</h2>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h4>{{ $totalRequests ?? 0 }}</h4>
                <p>إجمالي طلبات الصيانة</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h4>{{ $completedRequests ?? 0 }}</h4>
                <p>طلبات مكتملة</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h4>{{ $pendingRequests ?? 0 }}</h4>
                <p>طلبات معلقة</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>طلبات الصيانة</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>نوع الجهاز</th>
                        <th>الوصف</th>
                        <th>الفني</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($maintenanceRequests))
                        @foreach($maintenanceRequests as $request)
                        <tr>
                            <td>{{ $request->id }}</td>
                            <td>{{ $request->device_type }}</td>
                            <td>{{ $request->description }}</td>
                            <td>{{ $request->technician->name ?? 'غير محدد' }}</td>
                            <td>
                                @if($request->status === 'completed')
                                    <span class="badge bg-success">مكتمل</span>
                                @elseif($request->status === 'in_progress')
                                    <span class="badge bg-warning">قيد التنفيذ</span>
                                @else
                                    <span class="badge bg-secondary">معلق</span>
                                @endif
                            </td>
                            <td>{{ $request->created_at->format('Y-m-d') }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center">لا توجد طلبات صيانة</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
