{{-- resources/views/admin/users/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Chi tiết người dùng')
@section('page-description', 'Thông tin chi tiết tài khoản: ' . ($userData['username'] ?: $userData['email']))

@section('content')
    <div class="content-area">
        <!-- Header Actions -->
        <div class="page-actions">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
                <i class="ph ph-arrow-left"></i>
                Quay lại danh sách
            </a>
            @if($userData['can_edit'])
                <a href="{{ route('admin.users.edit', $userData['id']) }}" class="btn btn-primary">
                    <i class="ph ph-pencil"></i>
                    Chỉnh sửa
                </a>
            @endif
        </div>

        <!-- User Profile Card -->
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    {{ strtoupper(substr($userData['username'] ?: $userData['email'], 0, 2)) }}
                </div>
                <div class="profile-info">
                    <h1>{{ $userData['username'] ?: explode('@', $userData['email'])[0] }}</h1>
                    <p class="profile-email">{{ $userData['email'] }}</p>
                    <div class="profile-meta">
                        <span class="status-badge {{ $userData['is_active'] ? 'status-active' : 'status-inactive' }}">
                            @if($userData['is_active'])
                                <i class="ph ph-check-circle"></i>
                                Hoạt động
                            @else
                                <i class="ph ph-x-circle"></i>
                                Tạm khóa
                            @endif
                        </span>
                        @if($userData['email_verified_at'])
                            <span class="verified-badge">
                                <i class="ph ph-seal-check"></i>
                                Email đã xác thực
                            </span>
                        @else
                            <span class="unverified-badge">
                                <i class="ph ph-warning"></i>
                                Email chưa xác thực
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="quick-stats">
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="ph ph-article"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-number">{{ $userData['stats']['total_articles'] }}</div>
                        <div class="stat-label">Tổng bài viết</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="ph ph-check-circle"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-number">{{ $userData['stats']['published_articles'] }}</div>
                        <div class="stat-label">Đã xuất bản</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="ph ph-file-text"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-number">{{ $userData['stats']['draft_articles'] }}</div>
                        <div class="stat-label">Bản nháp</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="ph ph-activity"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-number">{{ $userData['stats']['total_activity_logs'] }}</div>
                        <div class="stat-label">Hoạt động</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Account Information -->
            <div class="info-card">
                <div class="card-header">
                    <h3>Thông tin tài khoản</h3>
                    <i class="ph ph-user"></i>
                </div>
                <div class="card-content">
                    <div class="info-group">
                        <label>ID</label>
                        <span>#{{ $userData['id'] }}</span>
                    </div>
                    <div class="info-group">
                        <label>Email</label>
                        <span>{{ $userData['email'] }}</span>
                    </div>
                    <div class="info-group">
                        <label>Username</label>
                        <span>{{ $userData['username'] ?: 'Chưa đặt' }}</span>
                    </div>
                    <div class="info-group">
                        <label>Trạng thái</label>
                        <span class="status-badge {{ $userData['is_active'] ? 'status-active' : 'status-inactive' }}">
                            @if($userData['is_active'])
                                <i class="ph ph-check-circle"></i>
                                Hoạt động
                            @else
                                <i class="ph ph-x-circle"></i>
                                Tạm khóa
                            @endif
                        </span>
                    </div>
                    <div class="info-group">
                        <label>Email xác thực</label>
                        <span>{{ $userData['email_verified_at'] ?: 'Chưa xác thực' }}</span>
                    </div>
                    <div class="info-group">
                        <label>Đăng nhập cuối</label>
                        <span>{{ $userData['last_login_at'] ?: 'Chưa đăng nhập' }}</span>
                    </div>
                    <div class="info-group">
                        <label>Ngày tạo</label>
                        <span>{{ $userData['created_at'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Roles & Permissions -->
            <div class="info-card">
                <div class="card-header">
                    <h3>Vai trò & Quyền hạn</h3>
                    <i class="ph ph-crown"></i>
                </div>
                <div class="card-content">
                    <div class="roles-section">
                        <h4>Vai trò được gán</h4>
                        <div class="roles-list">
                            @foreach($userData['roles'] as $role)
                                <div class="role-badge-large role-{{ $role->name }}">
                                    <div class="role-name">{{ $role->display_name }}</div>
                                    @if($role->description)
                                        <div class="role-description">{{ $role->description }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="permissions-section">
                        <h4>Quyền hạn tổng hợp</h4>
                        <div class="permissions-grid">
                            @foreach($userData['permissions']->groupBy('module') as $module => $permissions)
                                <div class="permission-module">
                                    <h5>{{ ucfirst($module) }}</h5>
                                    <div class="permission-list">
                                        @foreach($permissions as $permission)
                                            <span class="permission-item">
                                                {{ $permission->display_name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="info-card full-width">
                <div class="card-header">
                    <h3>Hoạt động gần đây</h3>
                    <i class="ph ph-clock-clockwise"></i>
                </div>
                <div class="card-content">
                    @if($userData['stats']['recent_activity_logs']->isNotEmpty())
                        <div class="activity-list">
                            @foreach($userData['stats']['recent_activity_logs'] as $log)
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        <i class="{{ $log->action_icon }}"></i>
                                    </div>
                                    <div class="activity-details">
                                        <div class="activity-description">{{ $log->description }}</div>
                                        <div class="activity-meta">
                                            <span class="activity-action">{{ $log->action }}</span>
                                            <span class="activity-time">{{ $log->created_at->diffForHumans() }}</span>
                                            @if($log->ip_address)
                                                <span class="activity-ip">IP: {{ $log->ip_address }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="ph ph-clock-clockwise"></i>
                            <p>Chưa có hoạt động nào được ghi nhận</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Buttons (if has manage permission) -->
        @can('users.manage')
            @if($userData['id'] !== auth()->id())
                <div class="action-section">
                    <h3>Thao tác quản lý</h3>
                    <div class="action-buttons-large">
                        <!-- Toggle Status -->
                        <form method="POST" action="{{ route('admin.users.toggle-status', $userData['id']) }}"
                            style="display: inline;"
                            onsubmit="return confirm('Bạn có chắc muốn {{ $userData['is_active'] ? 'khóa' : 'kích hoạt' }} tài khoản này?')">
                            @csrf
                            <button type="submit" class="btn {{ $userData['is_active'] ? 'btn-warning' : 'btn-success' }}">
                                <i class="ph {{ $userData['is_active'] ? 'ph-lock' : 'ph-lock-open' }}"></i>
                                {{ $userData['is_active'] ? 'Khóa tài khoản' : 'Kích hoạt tài khoản' }}
                            </button>
                        </form>

                        <!-- Reset Password -->
                        <form method="POST" action="{{ route('admin.users.reset-password', $userData['id']) }}"
                            style="display: inline;"
                            onsubmit="return confirm('Bạn có chắc muốn reset mật khẩu cho tài khoản này? Mật khẩu mới sẽ được gửi qua email.')">
                            @csrf
                            <button type="submit" class="btn btn-secondary">
                                <i class="ph ph-key"></i>
                                Reset mật khẩu
                            </button>
                        </form>

                        @if($userData['can_delete'])
                            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                <i class="ph ph-trash"></i>
                                Xóa tài khoản
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Delete Form (Hidden) -->
                @if($userData['can_delete'])
                    <form id="delete-form" method="POST" action="{{ route('admin.users.destroy', $userData['id']) }}"
                        style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            @endif
        @endcan
    </div>

    @push('styles')
        <style>
            .content-area {
                max-width: 1000px;
                margin: 0 auto;
            }

            .page-actions {
                display: flex;
                gap: 1rem;
                margin-bottom: 2rem;
            }

            .profile-card {
                background: var(--c-gray-800);
                border: 1px solid var(--c-gray-600);
                border-radius: 12px;
                padding: 2rem;
                margin-bottom: 2rem;
            }

            .profile-header {
                display: flex;
                align-items: center;
                gap: 2rem;
                margin-bottom: 2rem;
            }

            .profile-avatar {
                width: 100px;
                height: 100px;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--c-green-500), var(--c-blue-500));
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 600;
                font-size: 2rem;
                color: var(--c-gray-900);
                flex-shrink: 0;
            }

            .profile-info h1 {
                margin: 0 0 0.5rem 0;
                color: var(--c-text-primary);
                font-size: 2rem;
            }

            .profile-email {
                margin: 0 0 1rem 0;
                color: var(--c-text-secondary);
                font-size: 1.125rem;
            }

            .profile-meta {
                display: flex;
                align-items: center;
                gap: 1rem;
                flex-wrap: wrap;
            }

            .quick-stats {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 1.5rem;
            }

            .stat-item {
                display: flex;
                align-items: center;
                gap: 1rem;
                padding: 1.5rem;
                background: var(--c-gray-700);
                border-radius: 8px;
                border: 1px solid var(--c-gray-600);
            }

            .stat-icon {
                width: 48px;
                height: 48px;
                border-radius: 8px;
                background: var(--c-green-500);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.5rem;
                flex-shrink: 0;
            }

            .stat-details {
                flex: 1;
            }

            .stat-number {
                font-size: 1.5rem;
                font-weight: 600;
                color: var(--c-text-primary);
                margin-bottom: 0.25rem;
            }

            .stat-label {
                color: var(--c-text-tertiary);
                font-size: 0.875rem;
            }

            .content-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 2rem;
                margin-bottom: 2rem;
            }

            @media (max-width: 900px) {
                .content-grid {
                    grid-template-columns: 1fr;
                }
            }

            .info-card {
                background: var(--c-gray-800);
                border: 1px solid var(--c-gray-600);
                border-radius: 12px;
                padding: 1.5rem;
            }

            .info-card.full-width {
                grid-column: 1 / -1;
            }

            .card-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1.5rem;
                padding-bottom: 1rem;
                border-bottom: 1px solid var(--c-gray-600);
            }

            .card-header h3 {
                margin: 0;
                color: var(--c-text-primary);
                font-size: 1.125rem;
                font-weight: 600;
            }

            .card-header i {
                font-size: 1.25rem;
                color: var(--c-text-tertiary);
            }

            .card-content {
                color: var(--c-text-secondary);
            }

            .info-group {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.75rem 0;
                border-bottom: 1px solid var(--c-gray-700);
            }

            .info-group:last-child {
                border-bottom: none;
            }

            .info-group label {
                font-weight: 500;
                color: var(--c-text-tertiary);
                font-size: 0.875rem;
            }

            .info-group span {
                color: var(--c-text-primary);
                font-weight: 500;
            }

            .roles-section {
                margin-bottom: 2rem;
            }

            .roles-section h4 {
                margin: 0 0 1rem 0;
                color: var(--c-text-primary);
                font-size: 1rem;
                font-weight: 600;
            }

            .roles-list {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
            }

            .role-badge-large {
                padding: 1rem;
                border-radius: 8px;
                border: 1px solid;
            }

            .role-admin {
                background: rgba(239, 68, 68, 0.1);
                border-color: rgba(239, 68, 68, 0.2);
                color: var(--c-red-400);
            }

            .role-editor {
                background: rgba(59, 130, 246, 0.1);
                border-color: rgba(59, 130, 246, 0.2);
                color: var(--c-blue-400);
            }

            .role-viewer {
                background: rgba(107, 114, 128, 0.1);
                border-color: rgba(107, 114, 128, 0.2);
                color: var(--c-gray-400);
            }

            .role-badge-large .role-name {
                font-weight: 600;
                margin-bottom: 0.25rem;
            }

            .role-badge-large .role-description {
                font-size: 0.875rem;
                opacity: 0.8;
            }

            .permissions-section h4 {
                margin: 0 0 1rem 0;
                color: var(--c-text-primary);
                font-size: 1rem;
                font-weight: 600;
            }

            .permissions-grid {
                display: grid;
                gap: 1rem;
            }

            .permission-module {
                border: 1px solid var(--c-gray-600);
                border-radius: 8px;
                padding: 1rem;
            }

            .permission-module h5 {
                margin: 0 0 0.75rem 0;
                color: var(--c-text-primary);
                font-size: 0.875rem;
                font-weight: 600;
                text-transform: capitalize;
            }

            .permission-list {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .permission-item {
                padding: 0.25rem 0.75rem;
                background: var(--c-gray-700);
                border: 1px solid var(--c-gray-600);
                border-radius: 12px;
                font-size: 0.75rem;
                color: var(--c-text-tertiary);
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

            .verified-badge {
                background: rgba(34, 197, 94, 0.1);
                color: var(--c-green-400);
                border: 1px solid rgba(34, 197, 94, 0.2);
                padding: 0.25rem 0.75rem;
                border-radius: 12px;
                font-size: 0.75rem;
                font-weight: 500;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
            }

            .unverified-badge {
                background: rgba(245, 158, 11, 0.1);
                color: var(--c-yellow-400);
                border: 1px solid rgba(245, 158, 11, 0.2);
                padding: 0.25rem 0.75rem;
                border-radius: 12px;
                font-size: 0.75rem;
                font-weight: 500;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
            }

            .activity-list {
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }

            .activity-item {
                display: flex;
                gap: 1rem;
                padding: 1rem;
                background: var(--c-gray-700);
                border-radius: 8px;
                border: 1px solid var(--c-gray-600);
            }

            .activity-icon {
                width: 40px;
                height: 40px;
                border-radius: 8px;
                background: var(--c-blue-500);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                flex-shrink: 0;
            }

            .activity-details {
                flex: 1;
                min-width: 0;
            }

            .activity-description {
                color: var(--c-text-primary);
                font-weight: 500;
                margin-bottom: 0.5rem;
            }

            .activity-meta {
                display: flex;
                gap: 1rem;
                font-size: 0.75rem;
                color: var(--c-text-tertiary);
                flex-wrap: wrap;
            }

            .activity-action {
                background: var(--c-gray-600);
                padding: 0.125rem 0.5rem;
                border-radius: 4px;
                font-family: monospace;
            }

            .empty-state {
                text-align: center;
                padding: 2rem;
                color: var(--c-text-tertiary);
            }

            .empty-state i {
                font-size: 3rem;
                margin-bottom: 1rem;
                opacity: 0.5;
            }

            .action-section {
                background: var(--c-gray-800);
                border: 1px solid var(--c-gray-600);
                border-radius: 12px;
                padding: 2rem;
            }

            .action-section h3 {
                margin: 0 0 1.5rem 0;
                color: var(--c-text-primary);
                font-size: 1.125rem;
                font-weight: 600;
            }

            .action-buttons-large {
                display: flex;
                gap: 1rem;
                flex-wrap: wrap;
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

            @media (max-width: 768px) {
                .profile-header {
                    flex-direction: column;
                    text-align: center;
                    gap: 1.5rem;
                }

                .quick-stats {
                    grid-template-columns: 1fr;
                }

                .action-buttons-large {
                    flex-direction: column;
                }

                .profile-info h1 {
                    font-size: 1.5rem;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            function confirmDelete() {
                if (confirm('Bạn có chắc muốn xóa tài khoản này?\n\nHành động này sẽ:\n- Xóa vĩnh viễn tài khoản người dùng\n- Xóa tất cả dữ liệu liên quan\n- Không thể hoàn tác\n\nNhấn OK để tiếp tục.')) {
                    document.getElementById('delete-form').submit();
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                // Add click to copy functionality for user ID and email
                const userInfo = document.querySelectorAll('.info-group span');

                userInfo.forEach(span => {
                    if (span.textContent.includes('#') || span.textContent.includes('@')) {
                        span.style.cursor = 'pointer';
                        span.title = 'Click để copy';

                        span.addEventListener('click', function () {
                            navigator.clipboard.writeText(this.textContent).then(() => {
                                // Visual feedback
                                const original = this.textContent;
                                this.textContent = 'Đã copy!';
                                this.style.color = 'var(--c-green-400)';

                                setTimeout(() => {
                                    this.textContent = original;
                                    this.style.color = '';
                                }, 1000);
                            }).catch(() => {
                                console.log('Không thể copy text');
                            });
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection
