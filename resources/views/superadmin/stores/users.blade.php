
@extends('layouts.app')

@section('title', 'مستخدمي المتجر')
@section('page-title', 'مستخدمي المتجر: ' . $store->name)

@section('sidebar')
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('superadmin.dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('superadmin.stores.index') }}" class="nav-link active">
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
        <a href="{{ route('superadmin.reports.index') }}" class="nav-link">
            <i class="fas fa-chart-bar me-2"></i>
            التقارير والإحصائيات
        </a>
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>مستخدمي المتجر: {{ $store->name }}</h2>
    <div>
        <a href="{{ route('superadmin.stores.users.create', $store) }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>إضافة مستخدم جديد
        </a>
        <a href="{{ route('superadmin.stores.show', $store) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>العودة للمتجر
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-users me-2"></i>قائمة المستخدمين</h5>
    </div>
    <div class="card-body">
        @if($users->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>النوع</th>
                        <th>تاريخ الإنضمام</th>
                        <th>آخر دخول</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-primary text-white rounded-circle me-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                {{ $user->name }}
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-{{ $user->type === 'admin' ? 'primary' : 'success' }}">
                                {{ $user->type === 'admin' ? 'مدير' : 'كاشير' }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                        <td>{{ $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i') : 'لم يدخل بعد' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $user->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('superadmin.stores.users.destroy', [$store, $user]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('superadmin.stores.users.update', [$store, $user]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">تعديل المستخدم</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="name{{ $user->id }}" class="form-label">الاسم</label>
                                            <input type="text" class="form-control" id="name{{ $user->id }}" name="name" value="{{ $user->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email{{ $user->id }}" class="form-label">البريد الإلكتروني</label>
                                            <input type="email" class="form-control" id="email{{ $user->id }}" name="email" value="{{ $user->email }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="type{{ $user->id }}" class="form-label">النوع</label>
                                            <select class="form-select" id="type{{ $user->id }}" name="type" required>
                                                <option value="admin" {{ $user->type === 'admin' ? 'selected' : '' }}>مدير</option>
                                                <option value="cashier" {{ $user->type === 'cashier' ? 'selected' : '' }}>كاشير</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password{{ $user->id }}" class="form-label">كلمة المرور الجديدة (اختياري)</label>
                                            <input type="password" class="form-control" id="password{{ $user->id }}" name="password">
                                        </div>
                                        <div class="mb-3">
                                            <label for="password_confirmation{{ $user->id }}" class="form-label">تأكيد كلمة المرور</label>
                                            <input type="password" class="form-control" id="password_confirmation{{ $user->id }}" name="password_confirmation">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
        @else
        <div class="text-center py-4">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">لا يوجد مستخدمين في هذا المتجر</h5>
            <p class="text-muted">قم بإضافة مستخدم جديد للبدء</p>
        </div>
        @endif
    </div>
</div>
@endsection
