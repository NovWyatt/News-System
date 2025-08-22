@extends('layouts.auth')

@section('title', 'Chưa đăng nhập')
@section('page-title', 'Lỗi 401')

@section('content')
<div style="text-align: center; padding: 2rem 0;">
    <div style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.5;">🔐</div>
    <h3 style="margin-bottom: 1rem; font-weight: 300;">Chưa đăng nhập</h3>
    <p style="margin-bottom: 2rem; opacity: 0.8;">
        Bạn cần đăng nhập để truy cập trang này.
    </p>
    <a href="{{ route('admin.login') }}" class="btn-primary" style="display: inline-block; padding: 0.75rem 2rem; text-decoration: none; border-radius: 999px;">
        Đăng nhập ngay
    </a>
</div>
@endsection
