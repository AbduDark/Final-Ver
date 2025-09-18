
@extends('layouts.app')

@section('title', 'الملخص المالي - السوبر أدمن')
@section('page-title', 'الملخص المالي')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>الملخص المالي</h2>
            <form method="GET" class="d-flex">
                <select name="period" class="form-select me-2" onchange="this.form.submit()">
                    <option value="week" {{ $period == 'week' ? 'selected' : '' }}>الأسبوع</option>
                    <option value="month" {{ $period == 'month' ? 'selected' : '' }}>الشهر</option>
                    <option value="quarter" {{ $period == 'quarter' ? 'selected' : '' }}>الربع</option>
                    <option value="year" {{ $period == 'year' ? 'selected' : '' }}>السنة</option>
                </select>
            </form>
        </div>
    </div>
</div>

<!-- بطاقات الإحصائيات -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h5>إجمالي الإيرادات</h5>
                <h3>{{ number_format($revenue, 2) }} ج.م</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h5>إجمالي المصروفات</h5>
                <h3>{{ number_format($expenses, 2) }} ج.م</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h5>صافي الربح</h5>
                <h3>{{ number_format($revenue - $expenses, 2) }} ج.م</h3>
            </div>
        </div>
    </div>
</div>

<!-- الرسم البياني -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>اتجاه الإيرادات اليومية</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- أفضل المتاجر -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>أفضل المتاجر أداءً</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>المتجر</th>
                                <th>الإيرادات</th>
                                <th>النسبة من الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topStores as $store)
                            <tr>
                                <td>{{ $store->name }}</td>
                                <td>{{ number_format($store->revenue ?? 0, 2) }} ج.م</td>
                                <td>{{ $revenue > 0 ? number_format(($store->revenue ?? 0) / $revenue * 100, 1) : 0 }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($dailyRevenue->pluck('date')) !!},
        datasets: [{
            label: 'الإيرادات اليومية (ج.م)',
            data: {!! json_encode($dailyRevenue->pluck('revenue')) !!},
            borderColor: 'rgb(54, 162, 235)',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            tension: 0.1,
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush
