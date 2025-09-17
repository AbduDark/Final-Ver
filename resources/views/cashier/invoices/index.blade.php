
@extends('layouts.app')

@section('title', 'الفواتير')
@section('page-title', 'الفواتير')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('cashier.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('cashier.invoices.index') }}" class="nav-link active">
            <i class="fas fa-receipt me-2"></i>
            الفواتير
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('cashier.invoices.create') }}" class="nav-link">
            <i class="fas fa-plus me-2"></i>
            فاتورة جديدة
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>الفواتير</h2>
    <a href="{{ route('cashier.invoices.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>فاتورة جديدة
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>رقم الفاتورة</th>
                        <th>التاريخ</th>
                        <th>عدد الأصناف</th>
                        <th>المبلغ الإجمالي</th>
                        <th>الخصم</th>
                        <th>المبلغ الصافي</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $invoice->items->count() }}</td>
                        <td>{{ number_format($invoice->total_amount, 2) }} ج.م</td>
                        <td>{{ number_format($invoice->discount_amount, 2) }} ج.م</td>
                        <td>{{ number_format($invoice->net_amount, 2) }} ج.م</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('cashier.invoices.show', $invoice) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('cashier.invoices.print', $invoice) }}" class="btn btn-sm btn-secondary" target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $invoices->links() }}
    </div>
</div>
@endsection
