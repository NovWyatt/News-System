{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Quản lý người dùng')
@section('page-description', 'Quản lý tài khoản người dùng trong hệ thống')

@section('content')
<div class="content-area">
    <!-- Header Actions -->
    <div class="page-actions">
        @can('users.create')
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="ph ph-plus"></i>
            Thêm người dùng
        </a>
        @endcan
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <h3>Tổng người dùng</h3>
                <i class="ph ph-users"></i>
            </div>
            <div class="stat-number">{{ $stats['total_users'] ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-header">
                <h3>Đang hoạt động</h3>
                <i class="ph ph-check-circle"></i>
            </div>
            <div class="stat-number">{{ $stats['active_users'] ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-header">
                <h3>Tạm khóa</h3>
                <i class="ph ph-x-circle"></i>
            </div>
            <div class="stat-number">{{ $stats['inactive_users'] ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-header">
                <h3>Admin</h3>
                <i class="ph ph-crown"></i>
            </div>
            <div class="stat-number">{{ $stats['admin_users'] ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-header">
                <h3>Editor</h3>
                <i class="ph ph-pencil"></i>
            </div>
            <div class="stat-number">{{ $stats['editor_users'] ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-header">
                <h3>Email xác thực</h3>
                <i class="ph ph-seal-check"></i>
            </div>
            <div class="stat-number">{{ $stats['verified_users'] ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-header">
                <h3>Mới tháng này</h3>
                <i class="ph ph-calendar"></i>
            </div>
            <div class="stat-number">{{ $stats['recent_users'] ?? 0 }}</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <form method="GET" action="{{ route('admin.users.index') }}" class="filters-form">
            <div class="filter-group">
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Tìm kiếm theo email hoặc username..." class="form-input">
            </div>

            <div class="filter-group">
                <select name="role" class="form-select">
                    <option value="">Tất cả vai trò</option>
                    @foreach($filterOptions['roles'] as $role)
                    <option value="{{ $role->name }}" {{ ($filters['role'] ?? '') === $role->name ? 'selected' : '' }}>
                        {{ $role->display_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <select name="status" class="form-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>Đang hoạt động
                    </option>
                    <option value="inactive" {{ ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' }}>Tạm khóa
                    </option>
                </select>
            </div>

            <div class="filter-group">
                <select name="sort" class="form-select">
                    <option value="created_at" {{ ($filters['sort'] ?? 'created_at') === 'created_at' ? 'selected' : '' }}>Ngày tạo</option>
                    <option value="name" {{ ($filters['sort'] ?? '') === 'name' ? 'selected' : '' }}>Tên</option>
                    <option value="email" {{ ($filters['sort'] ?? '') === 'email' ? 'selected' : '' }}>Email</option>
                    <option value="last_login_at" {{ ($filters['sort'] ?? '') === 'last_login_at' ? 'selected' : '' }}>
                        Đăng nhập cuối</option>
                </select>
            </div>

            <div class="filter-group">
                <select name="order" class="form-select">
                    <option value="desc" {{ ($filters['order'] ?? 'desc') === 'desc' ? 'selected' : '' }}>Giảm dần
                    </option>
                    <option value="asc" {{ ($filters['order'] ?? '') === 'asc' ? 'selected' : '' }}>Tăng dần</option>
                </select>
            </div>

            <button type="submit" class="btn btn-secondary">
                <i class="ph ph-funnel"></i>
                Lọc
            </button>

            @if(request()->hasAny(['search', 'role', 'status', 'sort', 'order']))
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
                <i class="ph ph-x"></i>
                Xóa bộ lọc
            </a>
            @endif
        </form>
    </div>

    <!-- Users Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Người dùng</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th>Đăng nhập cuối</th>
                    <th>Ngày tạo</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="user-info">
                            <div class="user-avatar">
                                {{ strtoupper(substr($user->username ?: $user->email, 0, 2)) }}
                            </div>
                            <div class="user-details">
                                <div class="user-name">{{ $user->username ?: explode('@', $user->email)[0] }}</div>
                                <div class="user-email">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="roles-list">
                            @foreach($user->roles as $role)
                            <span class="role-badge role-{{ $role->name }}">
                                {{ $role->display_name }}
                            </span>
                            @endforeach
                        </div>
                    </td>
                    <td>
                        <span class="status-badge {{ $user->is_active ? 'status-active' : 'status-inactive' }}">
                            @if($user->is_active)
                            <i class="ph ph-check-circle"></i>
                            Hoạt động
                            @else
                            <i class="ph ph-x-circle"></i>
                            Tạm khóa
                            @endif
                        </span>
                    </td>
                    <td>
                        @if($user->last_login_at)
                        <span title="{{ $user->last_login_at->format('d/m/Y H:i:s') }}">
                            {{ $user->last_login_at->diffForHumans() }}
                        </span>
                        @else
                        <span class="text-muted">Chưa đăng nhập</span>
                        @endif
                    </td>
                    <td>
                        <span title="{{ $user->created_at->format('d/m/Y H:i:s') }}">
                            {{ $user->created_at->diffForHumans() }}
                        </span>
                    </td>
                    {{-- DEBUG: Thêm vào đầu view để kiểm tra --}}
                    {{-- @if(config('app.debug'))
                    <div style="background: #ef4444; color: white; padding: 1rem; margin-bottom: 1rem;">
                        <strong>DEBUG INFO:</strong><br>
                        Current User: {{ auth()->user()->email }} (ID: {{ auth()->user()->id }})<br>
                        Is Admin: {{ auth()->user()->isAdmin() ? 'YES' : 'NO' }}<br>
                        Has users.view: {{ auth()->user()->hasPermission('users.view') ? 'YES' : 'NO' }}<br>
                        Has users.edit: {{ auth()->user()->hasPermission('users.edit') ? 'YES' : 'NO' }}<br>
                        Has users.manage: {{ auth()->user()->hasPermission('users.manage') ? 'YES' : 'NO' }}<br>
                        Has users.delete: {{ auth()->user()->hasPermission('users.delete') ? 'YES' : 'NO' }}<br>
                        User Roles: {{ auth()->user()->roles->pluck('name')->implode(', ') }}
                    </div>
                    @endif --}}
                    <td class="text-center">
                        <div class="action-buttons">
                            @can('users.view')
                            <a href="{{ route('admin.users.show', $user) }}" class="btn-action btn-info" title="Xem chi tiết">
                                <i class="ph ph-eye"></i>
                            </a>
                            @endcan

                            @can('users.edit')
                            @if($user->id !== auth()->id())
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn-action btn-warning" title="Chỉnh sửa">
                                <i class="ph ph-pencil"></i>
                            </a>
                            @endif
                            @endcan

                            @can('users.manage')
                            @if($user->id !== auth()->id())
                            <!-- Toggle Status -->
                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn {{ $user->is_active ? 'khóa' : 'kích hoạt' }} tài khoản này?')">
                                @csrf
                                <button type="submit" class="btn-action {{ $user->is_active ? 'btn-warning' : 'btn-success' }}" title="{{ $user->is_active ? 'Khóa tài khoản' : 'Kích hoạt tài khoản' }}">
                                    <i class="ph {{ $user->is_active ? 'ph-lock' : 'ph-lock-open' }}"></i>
                                </button>
                            </form>

                            <!-- Reset Password -->
                            <form method="POST" action="{{ route('admin.users.reset-password', $user) }}" style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn reset mật khẩu cho tài khoản này?')">
                                @csrf
                                <button type="submit" class="btn-action btn-secondary" title="Reset mật khẩu">
                                    <i class="ph ph-key"></i>
                                </button>
                            </form>
                            @endif
                            @endcan

                            @can('users.delete')
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa tài khoản này? Hành động này không thể hoàn tác!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-danger" title="Xóa tài khoản">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </form>
                            @endif
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        @if(request()->hasAny(['search', 'role', 'status']))
                        Không tìm thấy người dùng nào phù hợp với bộ lọc.
                        @else
                        Chưa có người dùng nào trong hệ thống.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="pagination-container">
        {{ $users->links() }}
    </div>
    @endif
</div>

@push('styles')
<style>
    .content-area {
        max-width: 100%;
    }

    .page-actions {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 2rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }

    .stat-card {
        background: var(--c-gray-800);
        border: 1px solid var(--c-gray-600);
        border-radius: 8px;
        padding: 1.5rem;
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .stat-header h3 {
        margin: 0;
        font-size: 0.875rem;
        color: var(--c-text-tertiary);
        font-weight: 500;
    }

    .stat-header i {
        font-size: 1.25rem;
        color: var(--c-text-tertiary);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 600;
        color: var(--c-text-primary);
    }

    .filters-section {
        background: var(--c-gray-800);
        border: 1px solid var(--c-gray-600);
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .filters-form {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto auto;
        gap: 1rem;
        align-items: end;
    }

    @media (max-width: 1200px) {
        .filters-form {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .form-input,
    .form-select {
        padding: 0.75rem;
        border: 1px solid var(--c-gray-600);
        border-radius: 6px;
        background: var(--c-gray-700);
        color: var(--c-text-primary);
        font-size: 0.875rem;
    }

    .form-input:focus,
    .form-select:focus {
        outline: none;
        border-color: var(--c-green-500);
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    }

    .table-container {
        background: var(--c-gray-800);
        border: 1px solid var(--c-gray-600);
        border-radius: 8px;
        overflow: hidden;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        background: var(--c-gray-700);
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: var(--c-text-primary);
        border-bottom: 1px solid var(--c-gray-600);
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--c-gray-600);
        vertical-align: middle;
    }

    .data-table tr:hover {
        background: var(--c-gray-700);
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--c-green-500), var(--c-blue-500));
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--c-gray-900);
        flex-shrink: 0;
    }

    .user-details {
        min-width: 0;
    }

    .user-name {
        font-weight: 500;
        color: var(--c-text-primary);
        margin-bottom: 0.25rem;
    }

    .user-email {
        font-size: 0.875rem;
        color: var(--c-text-tertiary);
        word-break: break-all;
    }

    .roles-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .role-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .role-admin {
        background: var(--c-red-500);
        color: white;
    }

    .role-editor {
        background: var(--c-blue-500);
        color: white;
    }

    .role-viewer {
        background: var(--c-gray-500);
        color: white;
    }

    .status-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        font-size: 0.875rem;
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

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        font-size: 0.875rem;
    }

    .btn-info {
        background: var(--c-blue-500);
        color: white;
    }

    .btn-warning {
        background: var(--c-yellow-500);
        color: var(--c-gray-900);
    }

    .btn-success {
        background: var(--c-green-500);
        color: white;
    }

    .btn-secondary {
        background: var(--c-gray-600);
        color: var(--c-text-primary);
    }

    .btn-danger {
        background: var(--c-red-500);
        color: white;
    }

    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .text-center {
        text-align: center;
    }

    .text-muted {
        color: var(--c-text-tertiary);
    }

    .pagination-container {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
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

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

</style>
@endpush
@endsection
