
@extends('layouts.app')

@section('title', 'التقارير والإحصائيات')
@section('page-title', 'التقارير والإحصائيات')

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
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">التقارير والإحصائيات</h2>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                <h5 class="card-title">تقرير المبيعات</h5>
                <p class="card-text">عرض تقارير المبيعات حسب الفترة والمتجر</p>
                <a href="{{ route('superadmin.reports.sales') }}" class="btn btn-primary">
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
                <a href="{{ route('superadmin.reports.daily') }}" class="btn btn-success">
                    عرض التقرير
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-calendar-week fa-3x text-info mb-3"></i>
                <h5 class="card-title">التقرير الأسبوعي</h5>
                <p class="card-text">مراجعة أداء المبيعات الأسبوعية</p>
                <a href="{{ route('superadmin.reports.weekly') }}" class="btn btn-info">
                    عرض التقرير
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-calendar-alt fa-3x text-warning mb-3"></i>
                <h5 class="card-title">التقرير الشهري</h5>
                <p class="card-text">مراجعة أداء المبيعات الشهرية</p>
                <a href="{{ route('superadmin.reports.monthly') }}" class="btn btn-warning">
                    عرض التقرير
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-boxes fa-3x text-secondary mb-3"></i>
                <h5 class="card-title">تقرير المخزون</h5>
                <p class="card-text">مراجعة حالة المخزون والمنتجات</p>
                <a href="{{ route('superadmin.reports.inventory') }}" class="btn btn-secondary">
                    عرض التقرير
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-history fa-3x text-dark mb-3"></i>
                <h5 class="card-title">سجل الأنشطة</h5>
                <p class="card-text">مراجعة جميع الأنشطة والعمليات</p>
                <a href="{{ route('superadmin.reports.activities') }}" class="btn btn-dark">
                    عرض التقرير
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-chart-pie fa-3x text-success mb-3"></i>
                <h5 class="card-title">تقرير الأرباح والخسائر</h5>
                <p class="card-text">تحليل مفصل للأرباح والتكاليف</p>
                <a href="{{ route('superadmin.reports.profit-loss') }}" class="btn btn-success">
                    عرض التقرير
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-star fa-3x text-warning mb-3"></i>
                <h5 class="card-title">أفضل المنتجات</h5>
                <p class="card-text">المنتجات الأكثر مبيعاً وربحية</p>
                <a href="{{ route('superadmin.reports.top-products') }}" class="btn btn-warning">
                    عرض التقرير
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-users fa-3x text-info mb-3"></i>
                <h5 class="card-title">تحليل العملاء</h5>
                <p class="card-text">إحصائيات وسلوك العملاء</p>
                <a href="{{ route('superadmin.reports.customer-analysis') }}" class="btn btn-info">
                    عرض التقرير
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-user-tie fa-3x text-primary mb-3"></i>
                <h5 class="card-title">أداء الكاشيرز</h5>
                <p class="card-text">تقييم أداء موظفي المبيعات</p>
                <a href="{{ route('superadmin.reports.cashier-performance') }}" class="btn btn-primary">
                    عرض التقرير
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-calendar-check fa-3x text-secondary mb-3"></i>
                <h5 class="card-title">المقارنة اليومية</h5>
                <p class="card-text">مقارنة الأداء اليومي للمبيعات</p>
                <a href="{{ route('superadmin.reports.daily-comparison') }}" class="btn btn-secondary">
                    عرض التقرير
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                <h5 class="card-title">تنبيهات المخزون</h5>
                <p class="card-text">المنتجات قليلة أو منتهية المخزون</p>
                <a href="{{ route('superadmin.reports.low-stock-alert') }}" class="btn btn-danger">
                    عرض التقرير
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">إرشادات التقارير</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>تقارير المبيعات:</h6>
                        <ul>
                            <li>يمكنك تصفية التقارير حسب التاريخ</li>
                            <li>عرض المبيعات حسب المتجر</li>
                            <li>تحليل الأداء الشهري والأسبوعي</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>تقارير المخزون:</h6>
                        <ul>
                            <li>مراقبة المنتجات منخفضة المخزون</li>
                            <li>تقييم قيمة المخزون الإجمالية</li>
                            <li>تتبع حركة المنتجات</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
