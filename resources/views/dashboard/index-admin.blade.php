@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-3">مرحباً بك في لوحة التحكم</h5>
                    <p class="mb-4 text-body-secondary">
                        يمكنك إدارة المحتوى والمستخدمين والإعدادات من القائمة الجانبية.
                    </p>
                    <p class="mb-0 text-body-secondary small">
                        مسجل الدخول كـ: <strong>{{ auth('admin')->user()->name }}</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
