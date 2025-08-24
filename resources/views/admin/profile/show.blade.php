@extends('layouts.admin')

@section('title', 'Hồ sơ cá nhân')
@section('page-description', 'Quản lý thông tin tài khoản và cài đặt bảo mật')

@section('content')
    <!-- Profile Information Form -->
    <div class="content-section">
        <div style="display: flex; align-items: center; margin-bottom: 1.5rem;">
            <i class="ph  ph-user-gear" style="font-size: 1.5rem; margin-right: 0.75rem; color: var(--c-green-500);"></i>
            <h2 style="margin: 0;">Thông tin cá nhân</h2>
        </div>

        <form action="{{ route('admin.profile.update') }}" method="POST" id="profileForm">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--c-text-primary);">
                        <i class="ph  ph-envelope" style="margin-right: 0.5rem; color: var(--c-text-tertiary);"></i>
                        Email <span style="color: var(--c-red-500);">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $userData['email']) }}" required
                        style="width: 100%; padding: 0.75rem; border: 1px solid var(--c-gray-600); border-radius: 6px; background-color: var(--c-gray-700); color: var(--c-text-primary); font-size: 0.875rem; transition: all 0.3s ease;"
                        onfocus="this.style.borderColor='var(--c-green-500)'; this.style.boxShadow='0 0 0 2px rgba(69, 255, 188, 0.2)'"
                        onblur="this.style.borderColor='var(--c-gray-600)'; this.style.boxShadow='none'">
                    @error('email')
                        <div style="color: var(--c-red-500); font-size: 0.75rem; margin-top: 0.25rem;">
                            <i class="ph  ph-warning-circle" style="margin-right: 0.25rem;"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--c-text-primary);">
                        <i class="ph  ph-user" style="margin-right: 0.5rem; color: var(--c-text-tertiary);"></i>
                        Username <span style="color: var(--c-text-tertiary); font-size: 0.75rem;">(tùy chọn)</span>
                    </label>
                    <input type="text" name="username" value="{{ old('username', $userData['username']) }}"
                        placeholder="Để trống nếu không muốn đặt username"
                        style="width: 100%; padding: 0.75rem; border: 1px solid var(--c-gray-600); border-radius: 6px; background-color: var(--c-gray-700); color: var(--c-text-primary); font-size: 0.875rem; transition: all 0.3s ease;"
                        onfocus="this.style.borderColor='var(--c-green-500)'; this.style.boxShadow='0 0 0 2px rgba(69, 255, 188, 0.2)'"
                        onblur="this.style.borderColor='var(--c-gray-600)'; this.style.boxShadow='none'">
                    <div style="color: var(--c-text-tertiary); font-size: 0.75rem; margin-top: 0.25rem;">
                        <i class="ph  ph-info" style="margin-right: 0.25rem;"></i>
                        Username sẽ được hiển thị thay vì email khi đã đặt
                    </div>
                    @error('username')
                        <div style="color: var(--c-red-500); font-size: 0.75rem; margin-top: 0.25rem;">
                            <i class="ph  ph-warning-circle" style="margin-right: 0.25rem;"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn-primary" id="updateProfileBtn">
                    <i class="ph  ph-floppy-disk" style="margin-right: 0.5rem;"></i>
                    Cập nhật thông tin
                </button>
                <button type="reset" class="flat-button">
                    <i class="ph  ph-arrow-counter-clockwise" style="margin-right: 0.5rem;"></i>
                    Hoàn tác
                </button>
            </div>
        </form>
    </div>

    <!-- Change Password Form -->
    <div class="content-section">
        <div style="display: flex; align-items: center; margin-bottom: 1.5rem;">
            <i class="ph  ph-lock-key" style="font-size: 1.5rem; margin-right: 0.75rem; color: var(--c-orange-500);"></i>
            <h2 style="margin: 0;">Thay đổi mật khẩu</h2>
        </div>

        <form action="{{ route('admin.profile.password') }}" method="POST" id="passwordForm">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--c-text-primary);">
                    <i class="ph  ph-key" style="margin-right: 0.5rem; color: var(--c-text-tertiary);"></i>
                    Mật khẩu hiện tại <span style="color: var(--c-red-500);">*</span>
                </label>
                <div style="position: relative;">
                    <input type="password" name="current_password" id="currentPassword" required
                        style="width: 100%; padding: 0.75rem; padding-right: 3rem; border: 1px solid var(--c-gray-600); border-radius: 6px; background-color: var(--c-gray-700); color: var(--c-text-primary); font-size: 0.875rem; transition: all 0.3s ease;"
                        onfocus="this.style.borderColor='var(--c-orange-500)'; this.style.boxShadow='0 0 0 2px rgba(255, 167, 38, 0.2)'"
                        onblur="this.style.borderColor='var(--c-gray-600)'; this.style.boxShadow='none'">
                    <button type="button" onclick="togglePassword('currentPassword', this)"
                        style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--c-text-tertiary); cursor: pointer; padding: 0; font-size: 1rem;"
                        title="Hiển thị/ẩn mật khẩu">
                        <i class="ph  ph-eye"></i>
                    </button>
                </div>
                @error('current_password')
                    <div style="color: var(--c-red-500); font-size: 0.75rem; margin-top: 0.25rem;">
                        <i class="ph  ph-warning-circle" style="margin-right: 0.25rem;"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--c-text-primary);">
                        <i class="ph  ph-lock" style="margin-right: 0.5rem; color: var(--c-text-tertiary);"></i>
                        Mật khẩu mới <span style="color: var(--c-red-500);">*</span>
                    </label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="newPassword" required minlength="8"
                            style="width: 100%; padding: 0.75rem; padding-right: 3rem; border: 1px solid var(--c-gray-600); border-radius: 6px; background-color: var(--c-gray-700); color: var(--c-text-primary); font-size: 0.875rem; transition: all 0.3s ease;"
                            onfocus="this.style.borderColor='var(--c-orange-500)'; this.style.boxShadow='0 0 0 2px rgba(255, 167, 38, 0.2)'"
                            onblur="this.style.borderColor='var(--c-gray-600)'; this.style.boxShadow='none'"
                            oninput="checkPasswordStrength(this.value)">
                        <button type="button" onclick="togglePassword('newPassword', this)"
                            style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--c-text-tertiary); cursor: pointer; padding: 0; font-size: 1rem;"
                            title="Hiển thị/ẩn mật khẩu">
                            <i class="ph  ph-eye"></i>
                        </button>
                    </div>
                    <div id="passwordStrength" style="margin-top: 0.5rem; font-size: 0.75rem;"></div>
                    @error('password')
                        <div style="color: var(--c-red-500); font-size: 0.75rem; margin-top: 0.25rem;">
                            <i class="ph  ph-warning-circle" style="margin-right: 0.25rem;"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--c-text-primary);">
                        <i class="ph  ph-check" style="margin-right: 0.5rem; color: var(--c-text-tertiary);"></i>
                        Xác nhận mật khẩu <span style="color: var(--c-red-500);">*</span>
                    </label>
                    <div style="position: relative;">
                        <input type="password" name="password_confirmation" id="confirmPassword" required
                            style="width: 100%; padding: 0.75rem; padding-right: 3rem; border: 1px solid var(--c-gray-600); border-radius: 6px; background-color: var(--c-gray-700); color: var(--c-text-primary); font-size: 0.875rem; transition: all 0.3s ease;"
                            onfocus="this.style.borderColor='var(--c-orange-500)'; this.style.boxShadow='0 0 0 2px rgba(255, 167, 38, 0.2)'"
                            onblur="this.style.borderColor='var(--c-gray-600)'; this.style.boxShadow='none'"
                            oninput="checkPasswordMatch()">
                        <button type="button" onclick="togglePassword('confirmPassword', this)"
                            style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--c-text-tertiary); cursor: pointer; padding: 0; font-size: 1rem;"
                            title="Hiển thị/ẩn mật khẩu">
                            <i class="ph  ph-eye"></i>
                        </button>
                    </div>
                    <div id="passwordMatch" style="margin-top: 0.5rem; font-size: 0.75rem;"></div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="flat-button" id="changePasswordBtn"
                    style="background-color: var(--c-orange-500); color: var(--c-gray-900);">
                    <i class="ph  ph-shield-check" style="margin-right: 0.5rem;"></i>
                    Thay đổi mật khẩu
                </button>
                <button type="reset" class="flat-button">
                    <i class="ph  ph-x" style="margin-right: 0.5rem;"></i>
                    Hủy bỏ
                </button>
            </div>
        </form>
    </div>

    <!-- Security Tips -->
    <div class="content-section" style="background-color: rgba(79, 172, 254, 0.1); border-color: var(--c-blue-500);">
        <div style="display: flex; align-items: center; margin-bottom: 1rem;">
            <i class="ph  ph-shield-check" style="font-size: 1.5rem; margin-right: 0.75rem; color: var(--c-blue-500);"></i>
            <h2 style="margin: 0;">Bảo mật tài khoản</h2>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
            <div
                style="display: flex; align-items: start; padding: 1rem; background-color: rgba(255, 255, 255, 0.05); border-radius: 6px;">
                <i class="ph  ph-password"
                    style="font-size: 1.25rem; margin-right: 0.75rem; color: var(--c-green-500); margin-top: 0.25rem;"></i>
                <div>
                    <h4 style="margin: 0 0 0.5rem 0; color: var(--c-text-primary);">Mật khẩu mạnh</h4>
                    <p style="margin: 0; font-size: 0.875rem; color: var(--c-text-secondary);">
                        Sử dụng ít nhất 8 ký tự với chữ hoa, chữ thường, số và ký tự đặc biệt
                    </p>
                </div>
            </div>

            <div
                style="display: flex; align-items: start; padding: 1rem; background-color: rgba(255, 255, 255, 0.05); border-radius: 6px;">
                <i class="ph  ph-clock"
                    style="font-size: 1.25rem; margin-right: 0.75rem; color: var(--c-orange-500); margin-top: 0.25rem;"></i>
                <div>
                    <h4 style="margin: 0 0 0.5rem 0; color: var(--c-text-primary);">Thay đổi định kỳ</h4>
                    <p style="margin: 0; font-size: 0.875rem; color: var(--c-text-secondary);">
                        Khuyên nên thay đổi mật khẩu mỗi 3-6 tháng để tăng cường bảo mật
                    </p>
                </div>
            </div>

            <div
                style="display: flex; align-items: start; padding: 1rem; background-color: rgba(255, 255, 255, 0.05); border-radius: 6px;">
                <i class="ph  ph-warning"
                    style="font-size: 1.25rem; margin-right: 0.75rem; color: var(--c-red-500); margin-top: 0.25rem;"></i>
                <div>
                    <h4 style="margin: 0 0 0.5rem 0; color: var(--c-text-primary);">Không chia sẻ</h4>
                    <p style="margin: 0; font-size: 0.875rem; color: var(--c-text-secondary);">
                        Không bao giờ chia sẻ thông tin đăng nhập với người khác
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('sidebar')
    <!-- Account Overview -->
    <section class="content-section">
        <h2>Thông tin tài khoản</h2>
        <div style="text-align: center; padding: 1rem 0;">
            <div
                style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, var(--c-green-500), var(--c-blue-500)); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2rem; font-weight: 600; color: var(--c-gray-900);">
                {{ strtoupper(substr($userData['display_name'], 0, 2)) }}
            </div>
            <h3 style="margin: 0 0 0.5rem 0; color: var(--c-text-primary);">{{ $userData['display_name'] }}</h3>
            <p style="margin: 0 0 1rem 0; color: var(--c-text-tertiary); font-size: 0.875rem;">{{ $userData['email'] }}</p>

            <!-- Account Details -->
            <div style="text-align: left; font-size: 0.875rem; color: var(--c-text-tertiary); margin-bottom: 1.5rem;">
                <div
                    style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--c-gray-600);">
                    <span><i class="ph  ph-hash" style="margin-right: 0.5rem;"></i>ID:</span>
                    <span style="color: var(--c-text-primary); font-weight: 600;">#{{ $userData['id'] }}</span>
                </div>
                @if($userData['username'])
                    <div
                        style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--c-gray-600);">
                        <span><i class="ph  ph-user" style="margin-right: 0.5rem;"></i>Username:</span>
                        <span style="color: var(--c-text-primary);">{{ $userData['username'] }}</span>
                    </div>
                @endif
                <div
                    style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--c-gray-600);">
                    <span><i class="ph  ph-check-circle" style="margin-right: 0.5rem;"></i>Trạng thái:</span>
                    @if($userData['is_active'])
                        <span class="badge badge-success" style="padding: 0.125rem 0.375rem;">Hoạt động</span>
                    @else
                        <span class="badge badge-secondary" style="padding: 0.125rem 0.375rem;">Không hoạt động</span>
                    @endif
                </div>
                <div
                    style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--c-gray-600);">
                    <span><i class="ph  ph-envelope" style="margin-right: 0.5rem;"></i>Email:</span>
                    @if($userData['email_verified_at'])
                        <span style="color: var(--c-green-500);"><i class="ph  ph-seal-check" style="margin-right: 0.25rem;"></i>Đã
                            xác thực</span>
                    @else
                        <span style="color: var(--c-orange-500);"><i class="ph  ph-seal-warning"
                                style="margin-right: 0.25rem;"></i>Chưa xác thực</span>
                    @endif
                </div>
                <div
                    style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--c-gray-600);">
                    <span><i class="ph  ph-calendar" style="margin-right: 0.5rem;"></i>Tạo lúc:</span>
                    <span style="color: var(--c-text-primary);">{{ $userData['created_at'] }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.5rem 0;">
                    <span><i class="ph  ph-clock" style="margin-right: 0.5rem;"></i>Đăng nhập cuối:</span>
                    <span style="color: var(--c-text-primary);">{{ $userData['last_login_at'] ?? 'Chưa có' }}</span>
                </div>
            </div>

            <!-- Role Badges -->
            <div style="margin-bottom: 1rem;">
                <h4 style="margin: 0 0 0.5rem 0; color: var(--c-text-primary); font-size: 0.875rem;">Vai trò</h4>
                <div style="display: flex; justify-content: center; gap: 0.5rem; flex-wrap: wrap;">
                    @foreach($userData['roles'] as $role)
                        <span class="badge badge-success">{{ $role['display_name'] }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Article Statistics -->
    <section class="content-section">
        <h2>Thống kê bài viết</h2>
        <div class="stats-grid">
            <div class="stat-card" style="text-align: center;">
                <h3>{{ $userData['stats']['total_articles'] }}</h3>
                <p>Tổng bài viết</p>
            </div>
            <div class="stat-card" style="text-align: center;">
                <h3>{{ $userData['stats']['published_articles'] }}</h3>
                <p>Đã xuất bản</p>
            </div>
            <div class="stat-card" style="text-align: center;">
                <h3>{{ $userData['stats']['draft_articles'] }}</h3>
                <p>Bản nháp</p>
            </div>
        </div>
    </section>

    <!-- Permissions -->
    <section class="content-section">
        <h2>Quyền hạn</h2>
        <div style="max-height: 300px; overflow-y: auto;">
            @foreach($userData['permissions']->groupBy('module') as $module => $permissions)
                <div style="margin-bottom: 1rem;">
                    <h4
                        style="font-size: 0.875rem; color: var(--c-text-primary); margin-bottom: 0.5rem; display: flex; align-items: center;">
                        @if($module === 'articles')
                            <i class="ph  ph-article" style="margin-right: 0.5rem; color: var(--c-green-500);"></i>
                        @elseif($module === 'users')
                            <i class="ph  ph-users" style="margin-right: 0.5rem; color: var(--c-blue-500);"></i>
                        @elseif($module === 'system')
                            <i class="ph  ph-gear" style="margin-right: 0.5rem; color: var(--c-orange-500);"></i>
                        @else
                            <i class="ph  ph-circle" style="margin-right: 0.5rem; color: var(--c-text-tertiary);"></i>
                        @endif
                        {{ ucfirst($module) }}
                    </h4>
                    @foreach($permissions as $permission)
                        <div
                            style="font-size: 0.75rem; color: var(--c-text-tertiary); margin-bottom: 0.25rem; padding-left: 1.5rem;">
                            <i class="ph  ph-check" style="margin-right: 0.25rem; color: var(--c-green-500);"></i>
                            {{ $permission['display_name'] }}
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>

    <!-- Quick Links -->
    <section class="content-section">
        <h2>Liên kết nhanh</h2>
        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
            <a href="{{ route('admin.dashboard') }}" class="flat-button" style="text-align: center;">
                <i class="ph  ph-house" style="margin-right: 0.5rem;"></i>
                Quay về Dashboard
            </a>
            <a href="#" class="flat-button" style="text-align: center;">
                <i class="ph  ph-article" style="margin-right: 0.5rem;"></i>
                Bài viết của tôi
            </a>
            <a href="#" class="flat-button" style="text-align: center;">
                <i class="ph  ph-question" style="margin-right: 0.5rem;"></i>
                Trợ giúp
            </a>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Toggle password visibility
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'ph  ph-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'ph  ph-eye';
            }
        }

        // Check password strength
        function checkPasswordStrength(password) {
            const strengthDiv = document.getElementById('passwordStrength');

            if (password.length === 0) {
                strengthDiv.innerHTML = '';
                return;
            }

            let strength = 0;
            let feedback = [];

            // Length check
            if (password.length >= 8) strength++;
            else feedback.push('ít nhất 8 ký tự');

            // Uppercase check
            if (/[A-Z]/.test(password)) strength++;
            else feedback.push('chữ hoa');

            // Lowercase check
            if (/[a-z]/.test(password)) strength++;
            else feedback.push('chữ thường');

            // Number check
            if (/\d/.test(password)) strength++;
            else feedback.push('số');

            // Special character check
            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength++;
            else feedback.push('ký tự đặc biệt');

            let strengthText = '';
            let strengthColor = '';

            if (strength <= 2) {
                strengthText = 'Yếu';
                strengthColor = 'var(--c-red-500)';
            } else if (strength <= 3) {
                strengthText = 'Trung bình';
                strengthColor = 'var(--c-orange-500)';
            } else if (strength <= 4) {
                strengthText = 'Mạnh';
                strengthColor = 'var(--c-green-500)';
            } else {
                strengthText = 'Rất mạnh';
                strengthColor = 'var(--c-green-500)';
            }

            strengthDiv.innerHTML = `
            <div style="color: ${strengthColor};">
                <i class="ph  ph-shield-check" style="margin-right: 0.25rem;"></i>
                Độ mạnh: ${strengthText}
            </div>
            ${feedback.length > 0 ? `<div style="color: var(--c-text-tertiary); margin-top: 0.25rem;">Thiếu: ${feedback.join(', ')}</div>` : ''}
        `;
        }

        // Check password match
        function checkPasswordMatch() {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const matchDiv = document.getElementById('passwordMatch');

            if (confirmPassword.length === 0) {
                matchDiv.innerHTML = '';
                return;
            }

            if (newPassword === confirmPassword) {
                matchDiv.innerHTML = '<div style="color: var(--c-green-500);"><i class="ph  ph-check-circle" style="margin-right: 0.25rem;"></i>Mật khẩu khớp</div>';
            } else {
                matchDiv.innerHTML = '<div style="color: var(--c-red-500);"><i class="ph  ph-x-circle" style="margin-right: 0.25rem;"></i>Mật khẩu không khớp</div>';
            }
        }

        // Form handling
        document.addEventListener('DOMContentLoaded', function () {
            // Profile form
            const profileForm = document.getElementById('profileForm');
            profileForm.addEventListener('submit', function (e) {
                const submitBtn = document.getElementById('updateProfileBtn');
                showLoading(submitBtn);
            });

            // Password form
            const passwordForm = document.getElementById('passwordForm');
            passwordForm.addEventListener('submit', function (e) {
                const newPassword = document.getElementById('newPassword').value;
                const confirmPassword = document.getElementById('confirmPassword').value;

                if (newPassword !== confirmPassword) {
                    e.preventDefault();
                    alert('Mật khẩu xác nhận không khớp!');
                    return;
                }

                const submitBtn = document.getElementById('changePasswordBtn');
                showLoading(submitBtn);
            });

            // Auto-save draft (simulate)
            let autoSaveTimeout;
            document.querySelectorAll('input[name="email"], input[name="username"]').forEach(input => {
                input.addEventListener('input', function () {
                    clearTimeout(autoSaveTimeout);
                    autoSaveTimeout = setTimeout(() => {
                        // Visual feedback for auto-save
                        const indicator = document.createElement('div');
                        indicator.style.cssText = `
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        background: var(--c-green-500);
                        color: var(--c-gray-900);
                        padding: 0.5rem 1rem;
                        border-radius: 6px;
                        font-size: 0.875rem;
                        z-index: 1000;
                        opacity: 0;
                        transform: translateY(-20px);
                        transition: all 0.3s ease;
                    `;
                        indicator.innerHTML = '<i class="ph  ph-check" style="margin-right: 0.5rem;"></i>Đã lưu tạm';
                        document.body.appendChild(indicator);

                        setTimeout(() => {
                            indicator.style.opacity = '1';
                            indicator.style.transform = 'translateY(0)';
                        }, 100);

                        setTimeout(() => {
                            indicator.style.opacity = '0';
                            indicator.style.transform = 'translateY(-20px)';
                            setTimeout(() => document.body.removeChild(indicator), 300);
                        }, 2000);
                    }, 1000);
                });
            });

            // Animate form elements on load
            const formElements = document.querySelectorAll('.content-section');
            formElements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    element.style.transition = 'all 0.5s ease';
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, index * 200);
            });

            // Reset form handlers
            document.querySelectorAll('button[type="reset"]').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();

                    if (confirm('Bạn có chắc chắn muốn hoàn tác tất cả thay đổi?')) {
                        this.closest('form').reset();

                        // Clear validation messages
                        document.querySelectorAll('#passwordStrength, #passwordMatch').forEach(el => {
                            el.innerHTML = '';
                        });

                        // Reset input styles
                        document.querySelectorAll('input').forEach(input => {
                            input.style.borderColor = 'var(--c-gray-600)';
                            input.style.boxShadow = 'none';
                        });
                    }
                });
            });

            // Keyboard shortcuts
            document.addEventListener('keydown', function (e) {
                // Ctrl/Cmd + S to save profile
                if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                    e.preventDefault();
                    const profileForm = document.getElementById('profileForm');
                    if (profileForm) {
                        profileForm.submit();
                    }
                }

                // Ctrl/Cmd + Enter to change password
                if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                    const passwordForm = document.getElementById('passwordForm');
                    const activeElement = document.activeElement;

                    if (passwordForm.contains(activeElement)) {
                        e.preventDefault();
                        passwordForm.submit();
                    }
                }
            });

            // Enhance user experience with tooltips
            const tooltipElements = document.querySelectorAll('[title]');
            tooltipElements.forEach(element => {
                element.addEventListener('mouseenter', function (e) {
                    const tooltip = document.createElement('div');
                    tooltip.className = 'custom-tooltip';
                    tooltip.textContent = this.title;
                    tooltip.style.cssText = `
                    position: absolute;
                    background: var(--c-gray-700);
                    color: var(--c-text-primary);
                    padding: 0.5rem;
                    border-radius: 4px;
                    font-size: 0.75rem;
                    white-space: nowrap;
                    z-index: 1001;
                    pointer-events: none;
                    border: 1px solid var(--c-gray-600);
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
                `;

                    document.body.appendChild(tooltip);

                    const rect = this.getBoundingClientRect();
                    tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
                    tooltip.style.top = rect.bottom + 8 + 'px';

                    this.removeAttribute('title');
                    this.setAttribute('data-original-title', tooltip.textContent);
                });

                element.addEventListener('mouseleave', function () {
                    const tooltip = document.querySelector('.custom-tooltip');
                    if (tooltip) {
                        document.body.removeChild(tooltip);
                    }

                    const originalTitle = this.getAttribute('data-original-title');
                    if (originalTitle) {
                        this.setAttribute('title', originalTitle);
                        this.removeAttribute('data-original-title');
                    }
                });
            });

            // Form validation feedback
            const inputs = document.querySelectorAll('input[required]');
            inputs.forEach(input => {
                input.addEventListener('blur', function () {
                    if (this.value.trim() === '') {
                        this.style.borderColor = 'var(--c-red-500)';
                        this.style.boxShadow = '0 0 0 2px rgba(255, 71, 87, 0.2)';
                    } else if (this.checkValidity()) {
                        this.style.borderColor = 'var(--c-green-500)';
                        this.style.boxShadow = '0 0 0 2px rgba(69, 255, 188, 0.2)';
                    }
                });

                input.addEventListener('input', function () {
                    if (this.checkValidity()) {
                        this.style.borderColor = 'var(--c-green-500)';
                        this.style.boxShadow = '0 0 0 2px rgba(69, 255, 188, 0.2)';
                    }
                });
            });
        });

        // Utility function to show success message
        function showSuccessMessage(message) {
            const successDiv = document.createElement('div');
            successDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, var(--c-green-500), var(--c-blue-500));
            color: var(--c-gray-900);
            padding: 1rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            z-index: 1002;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.4s ease;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        `;
            successDiv.innerHTML = `<i class="ph  ph-check-circle" style="margin-right: 0.5rem;"></i>${message}`;
            document.body.appendChild(successDiv);

            setTimeout(() => {
                successDiv.style.opacity = '1';
                successDiv.style.transform = 'translateX(0)';
            }, 100);

            setTimeout(() => {
                successDiv.style.opacity = '0';
                successDiv.style.transform = 'translateX(100%)';
                setTimeout(() => document.body.removeChild(successDiv), 400);
            }, 4000);
        }

        // Check if there are success messages from server
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', function () {
                showSuccessMessage('{{ session('success') }}');
            });
        @endif
    </script>
@endpush
