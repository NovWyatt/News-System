@extends('layouts.auth')

@section('title', 'Đăng nhập')
@section('page-title', 'Đăng nhập')

@section('content')
    <form action="{{ route('admin.login.post') }}" method="POST" class="form" id="loginForm">
        @csrf

        <!-- Login Field (Email or Username) -->
        <div class="form__field">
            <input type="text" name="login" id="login" placeholder="Email hoặc Username" value="{{ old('login') }}" required
                autocomplete="username" autofocus>
        </div>

        <!-- Password Field -->
        <div class="form__field">
            <input type="password" name="password" id="password" placeholder="••••••••••••" required
                autocomplete="current-password">
        </div>

        <!-- Remember Me -->
        <div class="checkbox-field">
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label for="remember">Ghi nhớ đăng nhập</label>
        </div>

        <!-- Submit Button -->
        <div class="form__field">
            <input type="submit" value="Đăng nhập" id="loginButton">
        </div>
    </form>
@endsection

@section('footer')
    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tất cả quyền được bảo lưu bởi Wyatt.</p>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');
            const originalButtonText = loginButton.value;

            loginForm.addEventListener('submit', function (e) {
                // Show loading state
                showLoading(loginButton);

                // Basic client-side validation
                const login = document.getElementById('login').value.trim();
                const password = document.getElementById('password').value;

                if (!login || !password) {
                    e.preventDefault();
                    hideLoading(loginButton, originalButtonText);

                    // Show error
                    const existingAlert = document.querySelector('.alert-error');
                    if (existingAlert) {
                        existingAlert.remove();
                    }

                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-error';
                    alertDiv.textContent = 'Vui lòng nhập đầy đủ thông tin đăng nhập.';

                    loginForm.insertBefore(alertDiv, loginForm.firstChild);
                    return;
                }

                if (password.length < 6) {
                    e.preventDefault();
                    hideLoading(loginButton, originalButtonText);

                    const existingAlert = document.querySelector('.alert-error');
                    if (existingAlert) {
                        existingAlert.remove();
                    }

                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-error';
                    alertDiv.textContent = 'Mật khẩu phải có ít nhất 6 ký tự.';

                    loginForm.insertBefore(alertDiv, loginForm.firstChild);
                    return;
                }
            });

            // Handle form submission errors
            if (document.querySelector('.alert-error')) {
                hideLoading(loginButton, originalButtonText);
            }

            // Enter key handler
            document.addEventListener('keypress', function (e) {
                if (e.key === 'Enter' && !loginButton.disabled) {
                    loginForm.submit();
                }
            });

            // Focus management
            const loginInput = document.getElementById('login');
            const passwordInput = document.getElementById('password');

            loginInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    passwordInput.focus();
                }
            });
        });
    </script>
@endpush
