@extends('layouts.app')

@section('title', 'إدارة الفواتير')
@section('page-title', 'إدارة الفواتير')

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
   <li class="nav-item">
       <a href="{{ route('admin.dashboard') }}" class="nav-link">
           <i class="fas fa-tachometer-alt me-2"></i>
           لوحة التحكم
       </a>
   </li>
   <li class="nav-item">
       <a href="{{ route('admin.invoices.index') }}" class="nav-link active">
           <i class="fas fa-receipt me-2"></i>
           إدارة الفواتير
       </a>
   </li>
   <li class="nav-item">
       <a href="{{ route('admin.invoices.create') }}" class="nav-link">
           <i class="fas fa-plus me-2"></i>
           فاتورة جديدة
       </a>
   </li>
   <li class="nav-item">
       <a href="{{ route('admin.products.index') }}" class="nav-link">
           <i class="fas fa-box me-2"></i>
           المنتجات
       </a>
   </li>
</ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
   <h2>إدارة الفواتير</h2>
   <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary">
       <i class="fas fa-plus me-2"></i>فاتورة جديدة
   </a>
</div>

<!-- البحث -->
<div class="card mb-4">
   <div class="card-body">
       <form method="GET" action="{{ route('admin.invoices.search') }}">
           <div class="input-group">
               <input type="text" name="q" class="form-control" placeholder="ابحث برقم الفاتورة أو اسم العميل أو رقم الهاتف...">
               <button class="btn btn-outline-secondary" type="submit">
                   <i class="fas fa-search"></i> بحث
               </button>
           </div>
       </form>
   </div>
</div>

<div class="card">
   <div class="card-body">
       <div class="table-responsive">
           <table class="table table-hover">
               <thead>
                   <tr>
                       <th>رقم الفاتورة</th>
                       <th>التاريخ</th>
                       <th>الكاشير</th>
                       <th>العميل</th>
                       <th>عدد الأصناف</th>
                       <th>المبلغ الإجمالي</th>
                       <th>الخصم</th>
                       <th>المبلغ الصافي</th>
                       <th>طريقة الدفع</th>
                       <th>الإجراءات</th>
                   </tr>
               </thead>
               <tbody>
                   @forelse($invoices as $invoice)
                   <tr>
                       <td>{{ $invoice->invoice_number }}</td>
                       <td>{{ $invoice->created_at->format('Y-m-d H:i') }}</td>
                       <td>{{ $invoice->user->name ?? 'غير محدد' }}</td>
                       <td>{{ $invoice->customer_name ?: 'عميل عادي' }}</td>
                       <td>{{ $invoice->items->count() }}</td>
                       <td>{{ number_format($invoice->total_amount, 2) }} ج.م</td>
                       <td>{{ number_format($invoice->discount, 2) }} ج.م</td>
                       <td>{{ number_format($invoice->net_amount, 2) }} ج.م</td>
                       <td>
                           @switch($invoice->payment_method)
                               @case('cash')
                                   <span class="badge bg-success">نقداً</span>
                                   @break
                               @case('card')
                                   <span class="badge bg-info">بطاقة</span>
                                   @break
                               @case('transfer')
                                   <span class="badge bg-warning">تحويل</span>
                                   @break
                               @default
                                   <span class="badge bg-secondary">غير محدد</span>
                           @endswitch
                       </td>
                       <td>
                           <div class="btn-group" role="group">
                               <a href="{{ route('admin.invoices.show', $invoice) }}" class="btn btn-sm btn-info">
                                   <i class="fas fa-eye"></i>
                               </a>
                               <a href="{{ route('admin.invoices.print', $invoice) }}" class="btn btn-sm btn-secondary" target="_blank">
                                   <i class="fas fa-print"></i>
                               </a>
                           </div>
                       </td>
                   </tr>
                   @empty
                   <tr>
                       <td colspan="10" class="text-center">لا توجد فواتير</td>
                   </tr>
                   @endforelse
               </tbody>
           </table>
       </div>

       {{ $invoices->links() }}
   </div>
</div>
@endsection