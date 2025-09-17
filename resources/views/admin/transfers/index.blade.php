
@extends('layouts.app')

@section('title', 'إدارة التحويلات')
@section('page-title', 'إدارة التحويلات')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.transfers.index') }}" class="nav-link active">
            <i class="fas fa-exchange-alt me-2"></i>
            التحويلات
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>إدارة التحويلات</h2>
    <a href="{{ route('admin.transfers.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>إنشاء تحويل جديد
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>رقم التحويل</th>
                        <th>نوع التحويل</th>
                        <th>المبلغ</th>
                        <th>العميل</th>
                        <th>رقم الهاتف</th>
                        <th>الحالة</th>
                        <th>المستخدم</th>
                        <th>التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transfers as $transfer)
                    <tr>
                        <td>{{ $transfer->transfer_number }}</td>
                        <td>
                            <span class="badge bg-info">
                                @switch($transfer->transfer_type)
                                    @case('instant_payment')
                                        دفع فوري
                                        @break
                                    @case('recharge_cards')
                                        كروت شحن
                                        @break
                                    @case('vodafone_cash')
                                        فودافون كاش
                                        @break
                                    @case('etisalat_cash')
                                        اتصالات كاش
                                        @break
                                    @case('orange_cash')
                                        أورانج كاش
                                        @break
                                    @default
                                        غير محدد
                                @endswitch
                            </span>
                        </td>
                        <td>{{ number_format($transfer->amount, 2) }} ريال</td>
                        <td>{{ $transfer->customer_name }}</td>
                        <td>{{ $transfer->customer_phone }}</td>
                        <td>
                            <span class="badge bg-{{ $transfer->status === 'completed' ? 'success' : 'warning' }}">
                                {{ $transfer->status === 'completed' ? 'مكتمل' : 'معلق' }}
                            </span>
                        </td>
                        <td>{{ $transfer->user->name }}</td>
                        <td>{{ $transfer->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $transfers->links() }}
    </div>
</div>
@endsection
