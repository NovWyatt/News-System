<div class="user-card">
    <div class="user-avatar">
        {{ strtoupper(substr($user->username ?: $user->email, 0, 2)) }}
    </div>
    <div class="user-info">
        <h4>{{ $user->username ?: explode('@', $user->email)[0] }}</h4>
        <p>{{ $user->email }}</p>
        <div class="user-roles">
            @foreach($user->roles as $role)
                <span class="role-badge role-{{ $role->name }}">
                    {{ $role->display_name }}
                </span>
            @endforeach
        </div>
    </div>
    <div class="user-status">
        <span class="status-badge {{ $user->is_active ? 'status-active' : 'status-inactive' }}">
            @if($user->is_active)
                <i class="ph ph-check-circle"></i>
                Hoạt động
            @else
                <i class="ph ph-x-circle"></i>
                Tạm khóa
            @endif
        </span>
    </div>
</div>

{{-- resources/views/admin/users/partials/user-stats.blade.php --}}
<div class="user-stats-widget">
    <h4>Thống kê hoạt động</h4>
    <div class="stats-list">
        <div class="stat-row">
            <span class="stat-label">Tổng bài viết:</span>
            <span class="stat-value">{{ $stats['total_articles'] ?? 0 }}</span>
        </div>
        <div class="stat-row">
            <span class="stat-label">Đã xuất bản:</span>
            <span class="stat-value">{{ $stats['published_articles'] ?? 0 }}</span>
        </div>
        <div class="stat-row">
            <span class="stat-label">Bản nháp:</span>
            <span class="stat-value">{{ $stats['draft_articles'] ?? 0 }}</span>
        </div>
        <div class="stat-row">
            <span class="stat-label">Hoạt động ghi nhận:</span>
            <span class="stat-value">{{ $stats['total_activity_logs'] ?? 0 }}</span>
        </div>
    </div>
</div>

{{-- resources/views/admin/users/partials/role-selector.blade.php --}}
<div class="role-selector">
    <label class="form-label required">Chọn vai trò</label>
    <div class="roles-grid">
        @foreach($roles as $role)
            <div class="role-option">
                <label class="role-label">
                    <input type="checkbox"
                           name="roles[]"
                           value="{{ $role->id }}"
                           class="role-checkbox"
                           {{ in_array($role->id, $selectedRoles ?? []) ? 'checked' : '' }}>
                    <div class="role-card">
                        <div class="role-header">
                            <h5>{{ $role->display_name }}</h5>
                            <span class="role-indicator"></span>
                        </div>
                        @if($role->description)
                            <p class="role-description">{{ $role->description }}</p>
                        @endif
                        <div class="role-permissions-count">
                            <i class="ph ph-key"></i>
                            {{ $role->permissions->count() }} quyền
                        </div>
                    </div>
                </label>
            </div>
        @endforeach
    </div>
</div>

<style>
    /* User Card Component */
    .user-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: var(--c-gray-800);
        border: 1px solid var(--c-gray-600);
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .user-card:hover {
        border-color: var(--c-gray-500);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .user-card .user-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--c-green-500), var(--c-blue-500));
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: var(--c-gray-900);
        flex-shrink: 0;
    }

    .user-card .user-info {
        flex: 1;
        min-width: 0;
    }

    .user-card .user-info h4 {
        margin: 0 0 0.25rem 0;
        color: var(--c-text-primary);
        font-weight: 600;
    }

    .user-card .user-info p {
        margin: 0 0 0.5rem 0;
        color: var(--c-text-tertiary);
        font-size: 0.875rem;
        word-break: break-all;
    }

    .user-card .user-roles {
        display: flex;
        gap: 0.25rem;
        flex-wrap: wrap;
    }

    /* User Stats Widget */
    .user-stats-widget {
        background: var(--c-gray-800);
        border: 1px solid var(--c-gray-600);
        border-radius: 8px;
        padding: 1.5rem;
    }

    .user-stats-widget h4 {
        margin: 0 0 1rem 0;
        color: var(--c-text-primary);
        font-weight: 600;
    }

    .stats-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .stat-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid var(--c-gray-700);
    }

    .stat-row:last-child {
        border-bottom: none;
    }

    .stat-label {
        color: var(--c-text-tertiary);
        font-size: 0.875rem;
    }

    .stat-value {
        color: var(--c-text-primary);
        font-weight: 600;
    }

    /* Role Selector Component */
    .role-selector {
        margin-bottom: 1.5rem;
    }

    .role-option {
        margin-bottom: 1rem;
    }

    .role-label {
        display: block;
        cursor: pointer;
        margin: 0;
    }

    .role-checkbox {
        display: none;
    }

    .role-card {
        padding: 1rem;
        border: 2px solid var(--c-gray-600);
        border-radius: 8px;
        background: var(--c-gray-700);
        transition: all 0.2s ease;
    }

    .role-checkbox:checked + .role-card {
        border-color: var(--c-green-500);
        background: rgba(34, 197, 94, 0.05);
    }

    .role-card:hover {
        border-color: var(--c-gray-500);
        transform: translateY(-1px);
    }

    .role-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .role-header h5 {
        margin: 0;
        color: var(--c-text-primary);
        font-weight: 600;
    }

    .role-indicator {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 2px solid var(--c-gray-500);
        transition: all 0.2s ease;
    }

    .role-checkbox:checked + .role-card .role-indicator {
        background: var(--c-green-500);
        border-color: var(--c-green-500);
        position: relative;
    }

    .role-checkbox:checked + .role-card .role-indicator::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 0.625rem;
        font-weight: bold;
    }

    .role-description {
        margin: 0 0 0.75rem 0;
        color: var(--c-text-secondary);
        font-size: 0.875rem;
        line-height: 1.4;
    }

    .role-permissions-count {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--c-text-tertiary);
        font-size: 0.75rem;
    }

    /* Utility Classes */
    .text-sm { font-size: 0.875rem; }
    .text-xs { font-size: 0.75rem; }
    .font-semibold { font-weight: 600; }
    .font-bold { font-weight: 700; }
    .uppercase { text-transform: uppercase; }
    .lowercase { text-transform: lowercase; }
    .capitalize { text-transform: capitalize; }

    .mb-1 { margin-bottom: 0.25rem; }
    .mb-2 { margin-bottom: 0.5rem; }
    .mb-3 { margin-bottom: 0.75rem; }
    .mb-4 { margin-bottom: 1rem; }

    .mt-1 { margin-top: 0.25rem; }
    .mt-2 { margin-top: 0.5rem; }
    .mt-3 { margin-top: 0.75rem; }
    .mt-4 { margin-top: 1rem; }

    .p-1 { padding: 0.25rem; }
    .p-2 { padding: 0.5rem; }
    .p-3 { padding: 0.75rem; }
    .p-4 { padding: 1rem; }

    .flex { display: flex; }
    .flex-col { flex-direction: column; }
    .flex-wrap { flex-wrap: wrap; }
    .items-center { align-items: center; }
    .justify-between { justify-content: space-between; }
    .justify-center { justify-content: center; }
    .gap-1 { gap: 0.25rem; }
    .gap-2 { gap: 0.5rem; }
    .gap-3 { gap: 0.75rem; }
    .gap-4 { gap: 1rem; }

    .w-full { width: 100%; }
    .h-full { height: 100%; }

    .rounded { border-radius: 4px; }
    .rounded-md { border-radius: 6px; }
    .rounded-lg { border-radius: 8px; }
    .rounded-full { border-radius: 50%; }

    .border { border: 1px solid var(--c-gray-600); }
    .border-t { border-top: 1px solid var(--c-gray-600); }
    .border-b { border-bottom: 1px solid var(--c-gray-600); }

    .bg-gray-700 { background-color: var(--c-gray-700); }
    .bg-gray-800 { background-color: var(--c-gray-800); }

    .text-primary { color: var(--c-text-primary); }
    .text-secondary { color: var(--c-text-secondary); }
    .text-tertiary { color: var(--c-text-tertiary); }

    .shadow-sm {
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .shadow {
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }

    .shadow-lg {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    /* Interactive elements */
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .hover-scale:hover {
        transform: scale(1.02);
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .cursor-not-allowed {
        cursor: not-allowed;
        opacity: 0.6;
    }

    /* Form enhancements */
    .form-input.success {
        border-color: var(--c-green-500);
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    }

    .form-input.warning {
        border-color: var(--c-yellow-500);
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
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
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: translate(-50%, -50%) rotate(0deg); }
        100% { transform: translate(-50%, -50%) rotate(360deg); }
    }

    /* Success/Error messages */
    .form-success {
        color: var(--c-green-400);
        font-size: 0.75rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .form-warning {
        color: var(--c-yellow-400);
        font-size: 0.75rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* Custom checkbox styles for better UX */
    .checkbox-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem;
        border-radius: 6px;
        transition: background-color 0.2s ease;
    }

    .checkbox-item:hover {
        background: var(--c-gray-700);
    }

    /* Enhanced table styles */
    .data-table th.sortable {
        cursor: pointer;
        user-select: none;
        position: relative;
    }

    .data-table th.sortable:hover {
        background: var(--c-gray-600);
    }

    .data-table th.sortable::after {
        content: '';
        position: absolute;
        right: 0.5rem;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        opacity: 0.3;
    }

    .data-table th.sortable.asc::after {
        border-bottom: 6px solid var(--c-text-tertiary);
        opacity: 1;
    }

    .data-table th.sortable.desc::after {
        border-top: 6px solid var(--c-text-tertiary);
        opacity: 1;
    }

    /* Badge variants */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
        line-height: 1;
    }

    .badge-sm {
        padding: 0.125rem 0.5rem;
        font-size: 0.625rem;
    }

    .badge-lg {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }

    .badge-success {
        background: rgba(34, 197, 94, 0.1);
        color: var(--c-green-400);
        border: 1px solid rgba(34, 197, 94, 0.2);
    }

    .badge-warning {
        background: rgba(245, 158, 11, 0.1);
        color: var(--c-yellow-400);
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .badge-error {
        background: rgba(239, 68, 68, 0.1);
        color: var(--c-red-400);
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .badge-info {
        background: rgba(59, 130, 246, 0.1);
        color: var(--c-blue-400);
        border: 1px solid rgba(59, 130, 246, 0.2);
    }

    .badge-neutral {
        background: rgba(107, 114, 128, 0.1);
        color: var(--c-gray-400);
        border: 1px solid rgba(107, 114, 128, 0.2);
    }

    /* Responsive grid helpers */
    .grid-responsive {
        display: grid;
        gap: 1rem;
    }

    .grid-responsive.cols-1 {
        grid-template-columns: 1fr;
    }

    .grid-responsive.cols-2 {
        grid-template-columns: repeat(2, 1fr);
    }

    .grid-responsive.cols-3 {
        grid-template-columns: repeat(3, 1fr);
    }

    .grid-responsive.cols-4 {
        grid-template-columns: repeat(4, 1fr);
    }

    @media (max-width: 768px) {
        .grid-responsive.cols-2,
        .grid-responsive.cols-3,
        .grid-responsive.cols-4 {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 992px) {
        .grid-responsive.cols-3,
        .grid-responsive.cols-4 {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Animation helpers */
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    .animate-bounce {
        animation: bounce 1s infinite;
    }

    @keyframes bounce {
        0%, 100% {
            transform: translateY(-25%);
            animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
        }
        50% {
            transform: none;
            animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
        }
    }
</style>
