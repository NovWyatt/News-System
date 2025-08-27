{{-- resources/views/admin/users/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Tạo người dùng mới')
@section('page-description', 'Thêm tài khoản người dùng mới vào hệ thống')

@section('content')
<div class="content-area">
    <!-- Back Button -->
    <div class="page-actions">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
            <i class="ph ph-arrow-left"></i>
            Quay lại danh sách
        </a>
    </div>

    <div class="form-container">
        <form method="POST" action="{{ route('admin.users.store') }}" class="user-form">
            @csrf

            <!-- Basic Information -->
            <div class="form-section">
                <h3 class="section-title">Thông tin cơ bản</h3>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="email" class="form-label required">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-input @error('email') error @enderror" placeholder="user@example.com" required>
                        @error('email')
                        <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" class="form-input @error('username') error @enderror" placeholder="Tùy chọn - để trống sẽ dùng email" maxlength="50">
                        @error('username')
                        <span class="form-error">{{ $message }}</span>
                        @enderror
                        <small class="form-help">Để trống sẽ tự động sử dụng phần đầu của email</small>
                    </div>
                </div>
            </div>

            <!-- Security -->
            <div class="form-section">
                <h3 class="section-title">Bảo mật</h3>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="password" class="form-label required">Mật khẩu</label>
                        <input type="password" id="password" name="password" class="form-input @error('password') error @enderror" placeholder="Tối thiểu 8 ký tự" required>
                        @error('password')
                        <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label required">Xác nhận mật khẩu</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Nhập lại mật khẩu" required>
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
                        <div class="role-item">
                            <label class="checkbox-label">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="checkbox-input" {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
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
                            <input type="checkbox" name="is_active" value="1" class="toggle-input" {{ old('is_active', true) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                            <div class="toggle-text">
                                <div class="toggle-title">Kích hoạt tài khoản</div>
                                <div class="toggle-description">Cho phép người dùng đăng nhập vào hệ thống</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-check"></i>
                    Tạo người dùng
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
                    <i class="ph ph-x"></i>
                    Hủy
                </a>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    .content-area {
        max-width: 800px;
        margin: 0 auto;
    }

    .page-actions {
        margin-bottom: 2rem;
    }

    .form-container {
        background: var(--c-gray-800);
        border: 1px solid var(--c-gray-600);
        border-radius: 12px;
        padding: 2rem;
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
        margin: 0 0 1.5rem 0;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--c-gray-600);
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
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
    }

    .role-item {
        border: 1px solid var(--c-gray-600);
        border-radius: 8px;
        padding: 1rem;
        transition: all 0.2s ease;
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

    .btn-outline {
        background: transparent;
        color: var(--c-text-tertiary);
        border: 1px solid var(--c-gray-600);
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-primary:hover {
        background: var(--c-green-600);
    }

    .btn-outline:hover {
        border-color: var(--c-gray-500);
        background: var(--c-gray-700);
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
                    // Auto-suggest username from email prefix
                    usernameInput.placeholder = `Gợi ý: ${emailParts[0]}`;
                }
            }
        });

        // Password strength indicator
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');

        function checkPasswordMatch() {
            if (confirmPasswordInput.value && passwordInput.value !== confirmPasswordInput.value) {
                confirmPasswordInput.setCustomValidity('Mật khẩu không khớp');
            } else {
                confirmPasswordInput.setCustomValidity('');
            }
        }

        passwordInput.addEventListener('input', checkPasswordMatch);
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);

        // Role selection helper
        const roleCheckboxes = document.querySelectorAll('input[name="roles[]"]');

        roleCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const roleItem = this.closest('.role-item');
                if (this.checked) {
                    roleItem.style.borderColor = 'var(--c-green-500)';
                    roleItem.style.background = 'rgba(34, 197, 94, 0.05)';
                } else {
                    roleItem.style.borderColor = 'var(--c-gray-600)';
                    roleItem.style.background = 'transparent';
                }
            });
        });
    });

</script>
@endpush
@endsection
