
@extends('layouts.app')

@section('title', 'تقرير أفضل المنتجات - السوبر أدمن')
@section('page-title', 'تقرير أفضل المنتجات')

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
        <h2>تقرير أفضل المنتجات</h2>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>فلترة التقرير</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('superadmin.reports.top-products') }}">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">من تاريخ</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">إلى تاريخ</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">عدد المنتجات</label>
                    <select name="limit" class="form-control">
                        <option value="10" {{ $limit == 10 ? 'selected' : '' }}>أفضل 10</option>
                        <option value="20" {{ $limit == 20 ? 'selected' : '' }}>أفضل 20</option>
                        <option value="50" {{ $limit == 50 ? 'selected' : '' }}>أفضل 50</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>عرض التقرير
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- أفضل المنتجات -->
<div class="card">
    <div class="card-header">
        <h5>أفضل {{ $limit }} منتج مبيعاً</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم المنتج</th>
                        <th>المتجر</th>
                        <th>الكمية المباعة</th>
                        <th>إجمالي المبيعات</th>
                        <th>عدد مرات البيع</th>
                        <th>متوسط سعر البيع</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topProducts as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->product->store->name }}</td>
                        <td>{{ $item->total_quantity }}</td>
                        <td>{{ number_format($item->total_sales, 2) }} ج.م</td>
                        <td>{{ $item->times_sold }}</td>
                        <td>{{ number_format($item->total_sales / $item->total_quantity, 2) }} ج.م</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
