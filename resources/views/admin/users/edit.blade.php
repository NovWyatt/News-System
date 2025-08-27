{{-- resources/views/admin/users/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Chỉnh sửa người dùng')
@section('page-description', 'Cập nhật thông tin tài khoản: ' . ($user->username ?: $user->email))

@section('content')
<div class="content-area">
    <!-- Back Button -->
    <div class="page-actions">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
            <i class="ph ph-arrow-left"></i>
            Quay lại danh sách
        </a>
        @can('users.view')
        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
            <i class="ph ph-eye"></i>
            Xem chi tiết
        </a>
        @endcan
    </div>

    <div class="form-container">
        <!-- User Info Header -->
        <div class="user-header">
            <div class="user-avatar-large">
                {{ strtoupper(substr($user->username ?: $user->email, 0, 2)) }}
            </div>
            <div class="user-header-info">
                <h2>{{ $user->username ?: explode('@', $user->email)[0] }}</h2>
                <p>{{ $user->email }}</p>
                <div class="user-meta">
                    <span class="status-badge {{ $user->is_active ? 'status-active' : 'status-inactive' }}">
                        @if($user->is_active)
                        <i class="ph ph-check-circle"></i>
                        Hoạt động
                        @else
                        <i class="ph ph-x-circle"></i>
                        Tạm khóa
                        @endif
                    </span>
                    <span class="text-muted">
                        Tham gia {{ $user->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="user-form">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="form-section">
                <h3 class="section-title">Thông tin cơ bản</h3>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="email" class="form-label required">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-input @error('email') error @enderror" placeholder="user@example.com" required>
                        @error('email')
                        <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" class="form-input @error('username') error @enderror" placeholder="Tùy chọn - để trống sẽ dùng email" maxlength="50">
                        @error('username')
                        <span class="form-error">{{ $message }}</span>
                        @enderror
                        <small class="form-help">Để trống sẽ tự động sử dụng phần đầu của email</small>
                    </div>
                </div>
            </div>

            <!-- Password Update -->
            <div class="form-section">
                <h3 class="section-title">Đổi mật khẩu</h3>
                <p class="section-description">Để trống nếu không muốn thay đổi mật khẩu</p>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="password" class="form-label">Mật khẩu mới</label>
                        <input type="password" id="password" name="password" class="form-input @error('password') error @enderror" placeholder="Để trống nếu không đổi">
                        @error('password')
                        <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Nhập lại mật khẩu mới">
                    </div>
                </div>
            </div>

            <!-- Roles & Permissions -->
            <div class="form-section">
                <h3 class="section-title">Vai trò & Quyền hạn</h3>

                <div class="form-group">
                    <label class="form-label required">Vai trò</label>
                    <div class="roles-grid">
                        @foreach($roles as $role)
                        <div class="role-item {{ in_array($role->id, $user->roles->pluck('id')->toArray()) ? 'selected' : '' }}">
                            <label class="checkbox-label">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="checkbox-input" {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <span class="checkbox-custom"></span>
                                <div class="role-info">
                                    <div class="role-name">{{ $role->display_name }}</div>
                                    @if($role->description)
                                    <div class="role-description">{{ $role->description }}</div>
                                    @endif
                                    <div class="role-permissions">
                                        {{ $role->permissions->count() }} quyền
                                    </div>
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('roles')
                    <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Account Settings -->
            <div class="form-section">
                <h3 class="section-title">Cài đặt tài khoản</h3>

                <div class="form-group">
                    <div class="toggle-group">
                        <label class="toggle-label">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" class="toggle-input" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                            <div class="toggle-text">
                                <div class="toggle-title">Kích hoạt tài khoản</div>
                                <div class="toggle-description">Cho phép người dùng đăng nhập vào hệ thống</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- User Stats -->
            <div class="form-section">
                <h3 class="section-title">Thống kê hoạt động</h3>

                <div class="stats-grid-small">
                    <div class="stat-item">
                        <div class="stat-value">{{ $user->articles->count() }}</div>
                        <div class="stat-label">Tổng bài viết</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $user->articles->where('status', 'published')->count() }}</div>
                        <div class="stat-label">Đã xuất bản</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $user->articles->where('status', 'draft')->count() }}</div>
                        <div class="stat-label">Bản nháp</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $user->activityLogs->count() }}</div>
                        <div class="stat-label">Hoạt động</div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-check"></i>
                    Cập nhật
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
                    <i class="ph ph-x"></i>
                    Hủy
                </a>
                @can('users.delete')
                @if($user->id !== auth()->id())
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                    <i class="ph ph-trash"></i>
                    Xóa tài khoản
                </button>
                @endif
                @endcan
            </div>
        </form>

        <!-- Delete Form (Hidden) -->
        @can('users.delete')
        @if($user->id !== auth()->id())
        <form id="delete-form" method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
        @endif
        @endcan
    </div>
</div>

@push('styles')
<style>
    .content-area {
        max-width: 800px;
        margin: 0 auto;
    }

    .page-actions {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .form-container {
        background: var(--c-gray-800);
        border: 1px solid var(--c-gray-600);
        border-radius: 12px;
        padding: 2rem;
    }

    .user-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2.5rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--c-gray-600);
    }

    .user-avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--c-green-500), var(--c-blue-500));
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.5rem;
        color: var(--c-gray-900);
        flex-shrink: 0;
    }

    .user-header-info h2 {
        margin: 0 0 0.5rem 0;
        color: var(--c-text-primary);
        font-size: 1.5rem;
    }

    .user-header-info p {
        margin: 0 0 0.75rem 0;
        color: var(--c-text-secondary);
        font-size: 1rem;
    }

    .user-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .form-section {
        margin-bottom: 2.5rem;
    }

    .form-section:last-of-type {
        margin-bottom: 0;
    }

    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--c-text-primary);
        margin: 0 0 0.5rem 0;
    }

    .section-description {
        color: var(--c-text-tertiary);
        font-size: 0.875rem;
        margin: 0 0 1.5rem 0;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--c-text-primary);
        font-size: 0.875rem;
    }

    .form-label.required::after {
        content: '*';
        color: var(--c-red-400);
        margin-left: 0.25rem;
    }

    .form-input,
    .form-select {
        padding: 0.875rem;
        border: 1px solid var(--c-gray-600);
        border-radius: 8px;
        background: var(--c-gray-700);
        color: var(--c-text-primary);
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .form-input:focus,
    .form-select:focus {
        outline: none;
        border-color: var(--c-green-500);
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    }

    .form-input.error {
        border-color: var(--c-red-500);
    }

    .form-error {
        color: var(--c-red-400);
        font-size: 0.75rem;
        margin-top: 0.5rem;
    }

    .form-help {
        color: var(--c-text-tertiary);
        font-size: 0.75rem;
        margin-top: 0.5rem;
    }

    .roles-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
        margin-top: 1rem;
    }

    .role-item {
        border: 1px solid var(--c-gray-600);
        border-radius: 8px;
        padding: 1rem;
        transition: all 0.2s ease;
    }

    .role-item.selected {
        border-color: var(--c-green-500);
        background: rgba(34, 197, 94, 0.05);
    }

    .role-item:hover {
        border-color: var(--c-gray-500);
        background: var(--c-gray-700);
    }

    .checkbox-label {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        cursor: pointer;
        margin: 0;
    }

    .checkbox-input {
        display: none;
    }

    .checkbox-custom {
        width: 20px;
        height: 20px;
        border: 2px solid var(--c-gray-500);
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .checkbox-input:checked+.checkbox-custom {
        background: var(--c-green-500);
        border-color: var(--c-green-500);
    }

    .checkbox-input:checked+.checkbox-custom::after {
        content: '✓';
        color: white;
        font-weight: bold;
        font-size: 0.75rem;
    }

    .role-info {
        flex: 1;
    }

    .role-name {
        font-weight: 600;
        color: var(--c-text-primary);
        margin-bottom: 0.25rem;
    }

    .role-description {
        color: var(--c-text-secondary);
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .role-permissions {
        color: var(--c-text-tertiary);
        font-size: 0.75rem;
    }

    .toggle-group {
        background: var(--c-gray-700);
        border: 1px solid var(--c-gray-600);
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
    }

    .toggle-label {
        display: flex;
        align-items: center;
        gap: 1rem;
        cursor: pointer;
        margin: 0;
    }

    .toggle-input {
        display: none;
    }

    .toggle-slider {
        width: 44px;
        height: 24px;
        background: var(--c-gray-600);
        border-radius: 12px;
        position: relative;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .toggle-slider::after {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 20px;
        height: 20px;
        background: white;
        border-radius: 50%;
        transition: all 0.2s ease;
    }

    .toggle-input:checked+.toggle-slider {
        background: var(--c-green-500);
    }

    .toggle-input:checked+.toggle-slider::after {
        transform: translateX(20px);
    }

    .toggle-text {
        flex: 1;
    }

    .toggle-title {
        font-weight: 500;
        color: var(--c-text-primary);
        margin-bottom: 0.25rem;
    }

    .toggle-description {
        color: var(--c-text-tertiary);
        font-size: 0.875rem;
    }

    .stats-grid-small {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .stat-item {
        text-align: center;
        padding: 1rem;
        background: var(--c-gray-700);
        border-radius: 8px;
        border: 1px solid var(--c-gray-600);
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--c-text-primary);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.75rem;
        color: var(--c-text-tertiary);
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-active {
        background: rgba(34, 197, 94, 0.1);
        color: var(--c-green-400);
        border: 1px solid rgba(34, 197, 94, 0.2);
    }

    .status-inactive {
        background: rgba(239, 68, 68, 0.1);
        color: var(--c-red-400);
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .text-muted {
        color: var(--c-text-tertiary);
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid var(--c-gray-600);
    }

    @media (max-width: 576px) {
        .form-actions {
            flex-direction: column;
        }
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 1.5rem;
        border: none;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.875rem;
    }

    .btn-primary {
        background: var(--c-green-500);
        color: white;
    }

    .btn-secondary {
        background: var(--c-gray-600);
        color: var(--c-text-primary);
    }

    .btn-outline {
        background: transparent;
        color: var(--c-text-tertiary);
        border: 1px solid var(--c-gray-600);
    }

    .btn-success {
        background: var(--c-green-500);
        color: white;
    }

    .btn-warning {
        background: var(--c-yellow-500);
        color: var(--c-gray-900);
    }

    .btn-danger {
        background: var(--c-red-500);
        color: white;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-primary:hover {
        background: var(--c-green-600);
    }

    .btn-secondary:hover {
        background: var(--c-gray-500);
    }

    .btn-outline:hover {
        border-color: var(--c-gray-500);
        background: var(--c-gray-700);
    }

    .btn-success:hover {
        background: var(--c-green-600);
    }

    .btn-warning:hover {
        background: var(--c-yellow-600);
    }

    .btn-danger:hover {
        background: var(--c-red-600);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            text-align: center;
            gap: 1.5rem;
        }

        .user-header-info h2 {
            font-size: 1.25rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .action-buttons-large {
            flex-direction: column;
        }

        .stats-grid-small {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .content-area {
            padding: 0 1rem;
        }

        .form-container {
            padding: 1.5rem;
        }

        .user-header {
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }

        .user-avatar-large {
            width: 60px;
            height: 60px;
            font-size: 1.25rem;
        }

        .stats-grid-small {
            grid-template-columns: 1fr;
        }

        .btn {
            justify-content: center;
            width: 100%;
        }
    }

    /* Loading states */
    .btn.loading {
        position: relative;
        color: transparent !important;
    }

    .btn.loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 16px;
        height: 16px;
        border: 2px solid currentColor;
        border-top: 2px solid transparent;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: translate(-50%, -50%) rotate(0deg);
        }

        100% {
            transform: translate(-50%, -50%) rotate(360deg);
        }
    }

    /* Form validation states */
    .form-input.success {
        border-color: var(--c-green-500);
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    }

    .form-success {
        color: var(--c-green-400);
        font-size: 0.75rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* Enhanced role selection */
    .role-item.selected {
        border-color: var(--c-green-500);
        background: rgba(34, 197, 94, 0.05);
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    }

    /* Accessibility improvements */
    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    /* Focus indicators */
    .form-input:focus,
    .form-select:focus,
    .btn:focus {
        outline: 2px solid var(--c-green-500);
        outline-offset: 2px;
    }

    .checkbox-input:focus+.checkbox-custom {
        outline: 2px solid var(--c-green-500);
        outline-offset: 2px;
    }

    .toggle-input:focus+.toggle-slider {
        outline: 2px solid var(--c-green-500);
        outline-offset: 2px;
    }

    /* High contrast mode */
    @media (prefers-contrast: high) {

        .form-input,
        .form-select,
        .btn {
            border-width: 2px;
        }

        .status-badge,
        .role-badge-large {
            border-width: 2px;
        }
    }

    /* Reduced motion */
    @media (prefers-reduced-motion: reduce) {

        .btn,
        .form-input,
        .role-item,
        .toggle-slider,
        .checkbox-custom {
            transition: none;
        }

        .btn:hover {
            transform: none;
        }
    }

    /* Print styles */
    @media print {

        .page-actions,
        .form-actions {
            display: none !important;
        }

        .form-container {
            border: 1px solid #000 !important;
            box-shadow: none !important;
        }

        .btn {
            display: none !important;
        }
    }

</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-generate username from email if username is empty
        const emailInput = document.getElementById('email');
        const usernameInput = document.getElementById('username');

        emailInput.addEventListener('input', function() {
            if (!usernameInput.value) {
                const emailParts = this.value.split('@');
                if (emailParts.length > 0) {
                    usernameInput.placeholder = `Gợi ý: ${emailParts[0]}`;
                }
            }
        });

        // Password strength and confirmation
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');

        function checkPasswordMatch() {
            if (confirmPasswordInput.value && passwordInput.value !== confirmPasswordInput.value) {
                confirmPasswordInput.setCustomValidity('Mật khẩu không khớp');
                confirmPasswordInput.classList.add('error');
            } else {
                confirmPasswordInput.setCustomValidity('');
                confirmPasswordInput.classList.remove('error');
                if (passwordInput.value && confirmPasswordInput.value) {
                    confirmPasswordInput.classList.add('success');
                }
            }
        }

        passwordInput.addEventListener('input', function() {
            checkPasswordMatch();
            // Show password strength indicator
            if (this.value.length > 0) {
                showPasswordStrength(this.value);
            } else {
                hidePasswordStrength();
            }
        });

        confirmPasswordInput.addEventListener('input', checkPasswordMatch);

        // Password strength indicator
        function showPasswordStrength(password) {
            let strength = 0;
            const checks = [
                password.length >= 8
                , /[a-z]/.test(password)
                , /[A-Z]/.test(password)
                , /[0-9]/.test(password)
                , /[^A-Za-z0-9]/.test(password)
            ];

            strength = checks.filter(Boolean).length;

            // Remove existing indicator
            const existing = document.querySelector('.password-strength');
            if (existing) existing.remove();

            // Create strength indicator
            const strengthDiv = document.createElement('div');
            strengthDiv.className = 'password-strength';

            const strengthText = ['Rất yếu', 'Yếu', 'Trung bình', 'Mạnh', 'Rất mạnh'][strength - 1] || 'Rất yếu';
            const strengthColor = ['#ef4444', '#f59e0b', '#eab308', '#22c55e', '#16a34a'][strength - 1] || '#ef4444';

            strengthDiv.innerHTML = `
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem;">
                <div style="flex: 1; height: 4px; background: var(--c-gray-600); border-radius: 2px; overflow: hidden;">
                    <div style="height: 100%; width: ${strength * 20}%; background: ${strengthColor}; transition: all 0.3s ease;"></div>
                </div>
                <span style="font-size: 0.75rem; color: ${strengthColor};">${strengthText}</span>
            </div>
        `;

            passwordInput.parentNode.appendChild(strengthDiv);
        }

        function hidePasswordStrength() {
            const existing = document.querySelector('.password-strength');
            if (existing) existing.remove();
        }

        // Role selection styling and logic
        const roleCheckboxes = document.querySelectorAll('input[name="roles[]"]');

        roleCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const roleItem = this.closest('.role-item');
                if (this.checked) {
                    roleItem.classList.add('selected');
                } else {
                    roleItem.classList.remove('selected');
                }

                // Update role count
                updateRoleCount();
            });
        });

        function updateRoleCount() {
            const selectedCount = document.querySelectorAll('input[name="roles[]"]:checked').length;
            const roleLabel = document.querySelector('.form-label[for="roles"]') ||
                document.querySelector('label:has(+ .roles-grid)') ||
                document.querySelector('.roles-grid').previousElementSibling;

            if (roleLabel) {
                // Remove existing count
                const existingCount = roleLabel.querySelector('.role-count');
                if (existingCount) existingCount.remove();

                // Add new count
                if (selectedCount > 0) {
                    const countSpan = document.createElement('span');
                    countSpan.className = 'role-count';
                    countSpan.style.cssText = `
                    margin-left: 0.5rem;
                    padding: 0.125rem 0.5rem;
                    background: var(--c-green-500);
                    color: white;
                    border-radius: 12px;
                    font-size: 0.75rem;
                    font-weight: 500;
                `;
                    countSpan.textContent = selectedCount;
                    roleLabel.appendChild(countSpan);
                }
            }
        }

        // Initialize role count on page load
        updateRoleCount();

        // Form submission handling
        const form = document.querySelector('.user-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.classList.add('loading');
                    submitBtn.disabled = true;

                    // Re-enable after 5 seconds as fallback
                    setTimeout(() => {
                        submitBtn.classList.remove('loading');
                        submitBtn.disabled = false;
                    }, 5000);
                }
            });
        }

        // Enhanced toggle interactions
        const toggleInputs = document.querySelectorAll('.toggle-input');
        toggleInputs.forEach(toggle => {
            toggle.addEventListener('change', function() {
                const toggleText = this.closest('.toggle-label').querySelector('.toggle-description');
                if (toggleText) {
                    if (this.checked) {
                        toggleText.textContent = 'Người dùng có thể đăng nhập và sử dụng hệ thống';
                    } else {
                        toggleText.textContent = 'Người dùng bị khóa và không thể đăng nhập';
                    }
                }
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + S to save
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                const submitBtn = document.querySelector('button[type="submit"]');
                if (submitBtn && !submitBtn.disabled) {
                    submitBtn.click();
                }
            }

            // Escape to cancel
            if (e.key === 'Escape') {
                const cancelBtn = document.querySelector('.btn-outline[href*="users.index"]');
                if (cancelBtn) {
                    window.location.href = cancelBtn.href;
                }
            }
        });

        // Auto-save draft functionality (optional)
        let autoSaveTimeout;
        const formInputs = document.querySelectorAll('.form-input, .form-select, .checkbox-input, .toggle-input');

        formInputs.forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    // Could implement auto-save here
                    console.log('Auto-save triggered');
                }, 2000);
            });
        });
    });

    function confirmDelete() {
        const modal = document.createElement('div');
        modal.className = 'delete-modal';
        modal.innerHTML = `
        <div class="modal-overlay" onclick="closeDeleteModal()">
            <div class="modal-content" onclick="event.stopPropagation()">
                <div class="modal-header">
                    <h3>Xác nhận xóa tài khoản</h3>
                    <button onclick="closeDeleteModal()" class="modal-close">
                        <i class="ph ph-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="warning-icon">
                        <i class="ph ph-warning-circle"></i>
                    </div>
                    <p><strong>Bạn có chắc muốn xóa tài khoản này?</strong></p>
                    <p>Hành động này sẽ:</p>
                    <ul>
                        <li>Xóa vĩnh viễn tài khoản người dùng</li>
                        <li>Xóa tất cả dữ liệu liên quan</li>
                        <li>Không thể hoàn tác</li>
                    </ul>
                </div>
                <div class="modal-actions">
                    <button onclick="closeDeleteModal()" class="btn btn-outline">Hủy</button>
                    <button onclick="executeDelete()" class="btn btn-danger">
                        <i class="ph ph-trash"></i>
                        Xóa tài khoản
                    </button>
                </div>
            </div>
        </div>
    `;

        // Add modal styles
        const modalStyles = document.createElement('style');
        modalStyles.textContent = `
        .delete-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1000;
            animation: fadeIn 0.2s ease-out;
        }

        .modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.75);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-content {
            background: var(--c-gray-800);
            border: 1px solid var(--c-gray-600);
            border-radius: 12px;
            max-width: 500px;
            width: 100%;
            animation: slideIn 0.3s ease-out;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 1.5rem 0 1.5rem;
        }

        .modal-header h3 {
            margin: 0;
            color: var(--c-text-primary);
            font-size: 1.25rem;
        }

        .modal-close {
            background: none;
            border: none;
            color: var(--c-text-tertiary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .modal-close:hover {
            background: var(--c-gray-700);
            color: var(--c-text-primary);
        }

        .modal-body {
            padding: 1.5rem;
            text-align: center;
        }

        .warning-icon {
            font-size: 3rem;
            color: var(--c-red-500);
            margin-bottom: 1rem;
        }

        .modal-body p {
            color: var(--c-text-secondary);
            margin-bottom: 1rem;
        }

        .modal-body ul {
            text-align: left;
            color: var(--c-text-tertiary);
            margin: 1rem 0;
            padding-left: 1.5rem;
        }

        .modal-body li {
            margin-bottom: 0.5rem;
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            padding: 0 1.5rem 1.5rem 1.5rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
    `;

        document.head.appendChild(modalStyles);
        document.body.appendChild(modal);

        // Focus trap
        const focusableElements = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];

        firstElement.focus();

        modal.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                if (e.shiftKey) {
                    if (document.activeElement === firstElement) {
                        lastElement.focus();
                        e.preventDefault();
                    }
                } else {
                    if (document.activeElement === lastElement) {
                        firstElement.focus();
                        e.preventDefault();
                    }
                }
            }

            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });
    }

    function closeDeleteModal() {
        const modal = document.querySelector('.delete-modal');
        if (modal) {
            modal.style.animation = 'fadeOut 0.2s ease-out';
            setTimeout(() => {
                modal.remove();
                // Remove modal styles
                const modalStyles = document.querySelector('style:last-of-type');
                if (modalStyles && modalStyles.textContent.includes('.delete-modal')) {
                    modalStyles.remove();
                }
            }, 200);
        }
    }

    function executeDelete() {
        const deleteForm = document.getElementById('delete-form');
        if (deleteForm) {
            // Add loading state to delete button
            const deleteBtn = document.querySelector('.modal-actions .btn-danger');
            if (deleteBtn) {
                deleteBtn.classList.add('loading');
                deleteBtn.disabled = true;
                deleteBtn.innerHTML = '<i class="ph ph-spinner"></i> Đang xóa...';
            }

            deleteForm.submit();
        }
    }

    // Form validation enhancements
    function validateForm() {
        let isValid = true;
        const requiredFields = document.querySelectorAll('[required]');

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('error');
                isValid = false;
            } else {
                field.classList.remove('error');
            }
        });

        // Check if at least one role is selected
        const roleCheckboxes = document.querySelectorAll('input[name="roles[]"]:checked');
        if (roleCheckboxes.length === 0) {
            // Show error for roles
            const rolesGrid = document.querySelector('.roles-grid');
            if (rolesGrid) {
                rolesGrid.style.borderColor = 'var(--c-red-500)';

                // Remove existing error message
                const existingError = rolesGrid.parentNode.querySelector('.form-error');
                if (existingError) existingError.remove();

                // Add error message
                const errorSpan = document.createElement('span');
                errorSpan.className = 'form-error';
                errorSpan.textContent = 'Vui lòng chọn ít nhất một vai trò.';
                rolesGrid.parentNode.appendChild(errorSpan);
            }
            isValid = false;
        } else {
            // Remove error styling
            const rolesGrid = document.querySelector('.roles-grid');
            if (rolesGrid) {
                rolesGrid.style.borderColor = '';
                const existingError = rolesGrid.parentNode.querySelector('.form-error');
                if (existingError) existingError.remove();
            }
        }

        return isValid;
    }

    // Real-time form validation
    document.addEventListener('input', function(e) {
        if (e.target.matches('.form-input[required]')) {
            if (e.target.value.trim()) {
                e.target.classList.remove('error');
                e.target.classList.add('success');
            } else {
                e.target.classList.remove('success');
            }
        }
    });

    // Enhanced user experience features
    function initializeEnhancements() {
        // Add tooltips for role permissions
        const roleItems = document.querySelectorAll('.role-item');
        roleItems.forEach(item => {
            const permissionsCount = item.querySelector('.role-permissions');
            if (permissionsCount) {
                item.title = `Vai trò này có ${permissionsCount.textContent}`;
            }
        });

        // Add loading states to form
        const form = document.querySelector('.user-form');
        if (form) {
            const originalSubmitHandler = form.onsubmit;
            form.onsubmit = function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    return false;
                }

                // Show loading overlay
                showLoadingOverlay();

                if (originalSubmitHandler) {
                    return originalSubmitHandler.call(this, e);
                }
            };
        }

        // Initialize stats updates
        updateUserStats();
    }

    function showLoadingOverlay() {
        const overlay = document.createElement('div');
        overlay.className = 'loading-overlay';
        overlay.innerHTML = `
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <p>Đang cập nhật thông tin...</p>
        </div>
    `;

        const overlayStyles = document.createElement('style');
        overlayStyles.textContent = `
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.75);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-content {
            text-align: center;
            color: var(--c-text-primary);
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid var(--c-gray-600);
            border-top: 3px solid var(--c-green-500);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem auto;
        }
    `;

        document.head.appendChild(overlayStyles);
        document.body.appendChild(overlay);
    }

    function updateUserStats() {
        // Could implement real-time stats updates here
        // For now, just ensure stats are properly formatted
        const statNumbers = document.querySelectorAll('.stat-value');
        statNumbers.forEach(stat => {
            const value = parseInt(stat.textContent);
            if (!isNaN(value)) {
                stat.textContent = value.toLocaleString('vi-VN');
            }
        });
    }

    // Initialize all enhancements
    initializeEnhancements();

</script>
@endpush
@endsection
