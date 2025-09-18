
@extends('layouts.app')

@section('title', 'تحليل العملاء - السوبر أدمن')
@section('page-title', 'تحليل العملاء')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('superadmin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
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
        <h2>تحليل العملاء</h2>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>فلترة التقرير</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('superadmin.reports.customer-analysis') }}">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">من تاريخ</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">إلى تاريخ</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>عرض التقرير
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ملخص العملاء -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h5>إجمالي العملاء</h5>
                <h3>{{ $totalCustomers }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h5>إجمالي الإنفاق</h5>
                <h3>{{ number_format($totalSpent, 2) }} ج.م</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h5>متوسط قيمة الطلب</h5>
                <h3>{{ number_format($averageOrderValue, 2) }} ج.م</h3>
            </div>
        </div>
    </div>
</div>

<!-- تفاصيل العملاء -->
<div class="card">
    <div class="card-header">
        <h5>تفاصيل العملاء</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>اسم العميل</th>
                        <th>رقم الهاتف</th>
                        <th>عدد الزيارات</th>
                        <th>إجمالي الإنفاق</th>
                        <th>متوسط الطلب</th>
                        <th>تصنيف العميل</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customerData as $customer)
                    <tr>
                        <td>{{ $customer->customer_name }}</td>
                        <td>{{ $customer->customer_phone }}</td>
                        <td>{{ $customer->visit_count }}</td>
                        <td>{{ number_format($customer->total_spent, 2) }} ج.م</td>
                        <td>{{ number_format($customer->average_order, 2) }} ج.م</td>
                        <td>
                            @if($customer->total_spent >= 1000)
                                <span class="badge bg-warning">عميل VIP</span>
                            @elseif($customer->visit_count >= 5)
                                <span class="badge bg-success">عميل مخلص</span>
                            @else
                                <span class="badge bg-secondary">عميل عادي</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
