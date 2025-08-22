@extends('layouts.auth')

@section('title', 'Không có quyền truy cập')
@section('page-title', 'Lỗi 403')

@section('content')
<div style="text-align: center; padding: 2rem 0;">
    <div style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.5;">🔒</div>
    <h3 style="margin-bottom: 1rem; font-weight: 300;">Không có quyền truy cập</h3>
    <p style="margin-bottom: 2rem; opacity: 0.8;">
        Bạn không có quyền truy cập vào trang này.<br>
        Vui lòng liên hệ quản trị viên nếu bạn cho rằng đây là lỗi.
    </p>
    <a href="{{ route('admin.login') }}" class="btn-primary" style="display: inline-block; padding: 0.75rem 2rem; text-decoration: none; border-radius: 999px;">
        Quay lại trang đăng nhập
    </a>
</div>
@endsection

