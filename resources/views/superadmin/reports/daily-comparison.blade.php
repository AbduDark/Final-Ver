
@extends('layouts.app')

@section('title', 'المقارنة اليومية - السوبر أدمن')
@section('page-title', 'المقارنة اليومية للمبيعات')

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
        <h2>المقارنة اليومية للمبيعات</h2>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>اختيار الفترة</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('superadmin.reports.daily-comparison') }}">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">عدد الأيام</label>
                    <select name="days" class="form-control">
                        <option value="7" {{ $days == 7 ? 'selected' : '' }}>آخر 7 أيام</option>
                        <option value="15" {{ $days == 15 ? 'selected' : '' }}>آخر 15 يوم</option>
                        <option value="30" {{ $days == 30 ? 'selected' : '' }}>آخر 30 يوم</option>
                        <option value="60" {{ $days == 60 ? 'selected' : '' }}>آخر 60 يوم</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>عرض التقرير
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ملخص الإحصائيات -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h5>متوسط المبيعات اليومية</h5>
                <h3>{{ number_format($averageDailySales, 2) }} ج.م</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h5>أفضل يوم</h5>
                <h6>{{ $bestDay ? $bestDay->date : 'لا يوجد' }}</h6>
                <h4>{{ $bestDay ? number_format($bestDay->daily_sales, 2) . ' ج.م' : '0 ج.م' }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h5>أضعف يوم</h5>
                <h6>{{ $worstDay ? $worstDay->date : 'لا يوجد' }}</h6>
                <h4>{{ $worstDay ? number_format($worstDay->daily_sales, 2) . ' ج.م' : '0 ج.م' }}</h4>
            </div>
        </div>
    </div>
</div>

<!-- المقارنة اليومية -->
<div class="card">
    <div class="card-header">
        <h5>المقارنة اليومية</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>عدد الفواتير</th>
                        <th>المبيعات</th>
                        <th>مقارنة بالمتوسط</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyData as $day)
                    <tr>
                        <td>{{ $day->date }}</td>
                        <td>{{ $day->invoice_count }}</td>
                        <td>{{ number_format($day->daily_sales, 2) }} ج.م</td>
                        <td>
                            @php
                                $percentage = $averageDailySales > 0 ? (($day->daily_sales - $averageDailySales) / $averageDailySales) * 100 : 0;
                            @endphp
                            <span class="{{ $percentage >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $percentage >= 0 ? '+' : '' }}{{ number_format($percentage, 1) }}%
                            </span>
                        </td>
                        <td>
                            @if($day->daily_sales >= $averageDailySales * 1.2)
                                <span class="badge bg-success">ممتاز</span>
                            @elseif($day->daily_sales >= $averageDailySales)
                                <span class="badge bg-primary">جيد</span>
                            @elseif($day->daily_sales >= $averageDailySales * 0.8)
                                <span class="badge bg-warning">متوسط</span>
                            @else
                                <span class="badge bg-danger">ضعيف</span>
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
