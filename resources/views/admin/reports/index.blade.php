
@extends('layouts.app')

@section('title', 'التقارير')
@section('page-title', 'التقارير')

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
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">التقارير</h2>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                <h5 class="card-title">تقرير المبيعات</h5>
                <p class="card-text">عرض تقارير المبيعات حسب الفترة</p>
                <a href="{{ route('admin.reports.sales') }}" class="btn btn-primary">
                    عرض التقرير
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-calendar-day fa-3x text-success mb-3"></i>
                <h5 class="card-title">التقرير اليومي</h5>
                <p class="card-text">مراجعة أداء المبيعات اليومية</p>
                <a href="{{ route('admin.reports.daily') }}" class="btn btn-success">
                    عرض التقرير
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-boxes fa-3x text-info mb-3"></i>
                <h5 class="card-title">تقرير المخزون</h5>
                <p class="card-text">مراجعة حالة المخزون والمنتجات</p>
                <a href="{{ route('admin.reports.inventory') }}" class="btn btn-info">
                    عرض التقرير
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-dollar-sign fa-3x text-warning mb-3"></i>
                <h5 class="card-title">قيمة المخزون</h5>
                <p class="card-text">تقرير بقيمة المخزون الحالية</p>
                <a href="{{ route('admin.reports.inventory-value') }}" class="btn btn-warning">
                    عرض التقرير
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-tools fa-3x text-secondary mb-3"></i>
                <h5 class="card-title">تقرير الصيانة</h5>
                <p class="card-text">تقرير بجميع طلبات الصيانة</p>
                <a href="{{ route('admin.reports.maintenance') }}" class="btn btn-secondary">
                    عرض التقرير
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-cash-register fa-3x text-dark mb-3"></i>
                <h5 class="card-title">إقفال يومي</h5>
                <p class="card-text">تقرير الإقفال اليومي للخزينة</p>
                <a href="{{ route('admin.reports.daily-closing') }}" class="btn btn-dark">
                    عرض التقرير
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-cube fa-3x text-primary mb-3"></i>
                <h5 class="card-title">تقرير المنتجات</h5>
                <p class="card-text">تقرير تفصيلي بجميع المنتجات</p>
                <a href="{{ route('admin.reports.products') }}" class="btn btn-primary">
                    عرض التقرير
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
