@extends('layouts.admin')

@section('title', 'Nhật ký hoạt động')
@section('page-description', 'Theo dõi và quản lý hoạt động hệ thống')

@section('content')
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>{{ number_format($stats['total_logs']) }}</h3>
            <p>
                <i class="ph  ph-activity" style="margin-right: 0.25rem; color: var(--c-text-tertiary);"></i>
                Tổng nhật ký
            </p>
        </div>
        <div class="stat-card">
            <h3>{{ number_format($stats['today_logs']) }}</h3>
            <p>
                <i class="ph  ph-calendar" style="margin-right: 0.25rem; color: var(--c-green-500);"></i>
                Hôm nay
            </p>
        </div>
        <div class="stat-card">
            <h3>{{ number_format($stats['this_week_logs']) }}</h3>
            <p>
                <i class="ph  ph-calendar-check" style="margin-right: 0.25rem; color: var(--c-blue-500);"></i>
                Tuần này
            </p>
        </div>
        <div class="stat-card">
            <h3>{{ number_format($stats['this_month_logs']) }}</h3>
            <p>
                <i class="ph  ph-calendar-star" style="margin-right: 0.25rem; color: var(--c-purple-500);"></i>
                Tháng này
            </p>
        </div>
    </div>

    <!-- Category Statistics -->
    <div class="content-section">
        <h2>Thống kê theo loại hoạt động</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div
                style="display: flex; align-items: center; padding: 1rem; background-color: var(--c-gray-600); border-radius: 6px; border-left: 4px solid var(--c-green-500);">
                <i class="ph  ph-sign-in" style="font-size: 2rem; margin-right: 1rem; color: var(--c-green-500);"></i>
                <div>
                    <div style="font-size: 1.25rem; font-weight: 600; color: var(--c-text-primary);">
                        {{ number_format($stats['login_logs']) }}</div>
                    <div style="font-size: 0.875rem; color: var(--c-text-tertiary);">Đăng nhập</div>
                </div>
            </div>
            <div
                style="display: flex; align-items: center; padding: 1rem; background-color: var(--c-gray-600); border-radius: 6px; border-left: 4px solid var(--c-blue-500);">
                <i class="ph  ph-article" style="font-size: 2rem; margin-right: 1rem; color: var(--c-blue-500);"></i>
                <div>
                    <div style="font-size: 1.25rem; font-weight: 600; color: var(--c-text-primary);">
                        {{ number_format($stats['article_logs']) }}</div>
                    <div style="font-size: 0.875rem; color: var(--c-text-tertiary);">Bài viết</div>
                </div>
            </div>
            <div
                style="display: flex; align-items: center; padding: 1rem; background-color: var(--c-gray-600); border-radius: 6px; border-left: 4px solid var(--c-purple-500);">
                <i class="ph  ph-users" style="font-size: 2rem; margin-right: 1rem; color: var(--c-purple-500);"></i>
                <div>
                    <div style="font-size: 1.25rem; font-weight: 600; color: var(--c-text-primary);">
                        {{ number_format($stats['user_logs']) }}</div>
                    <div style="font-size: 0.875rem; color: var(--c-text-tertiary);">Người dùng</div>
                </div>
            </div>
            <div
                style="display: flex; align-items: center; padding: 1rem; background-color: var(--c-gray-600); border-radius: 6px; border-left: 4px solid var(--c-orange-500);">
                <i class="ph  ph-gear" style="font-size: 2rem; margin-right: 1rem; color: var(--c-orange-500);"></i>
                <div>
                    <div style="font-size: 1.25rem; font-weight: 600; color: var(--c-text-primary);">
                        {{ number_format($stats['system_logs']) }}</div>
                    <div style="font-size: 0.875rem; color: var(--c-text-tertiary);">Hệ thống</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="content-section">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="margin: 0;">
                <i class="ph  ph-funnel" style="margin-right: 0.5rem; color: var(--c-green-500);"></i>
                Bộ lọc nhật ký
            </h2>
            <div style="display: flex; gap: 0.75rem;">
                <button class="flat-button" onclick="resetFilters()">
                    <i class="ph  ph-x" style="margin-right: 0.5rem;"></i>
                    Xóa bộ lọc
                </button>
                <a href="{{ route('admin.logs.export', request()->query()) }}" class="flat-button"
                    style="background-color: var(--c-green-500); color: var(--c-gray-900);">
                    <i class="ph  ph-download" style="margin-right: 0.5rem;"></i>
                    Xuất CSV
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.logs.index') }}" id="filterForm"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
            <!-- Search -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--c-text-primary);">
                    <i class="ph  ph-magnifying-glass" style="margin-right: 0.5rem; color: var(--c-text-tertiary);"></i>
                    Tìm kiếm
                </label>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                    placeholder="Tìm kiếm trong mô tả, hành động..."
                    style="width: 100%; padding: 0.75rem; border: 1px solid var(--c-gray-600); border-radius: 6px; background-color: var(--c-gray-700); color: var(--c-text-primary); font-size: 0.875rem;">
            </div>

            <!-- User Filter -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--c-text-primary);">
                    <i class="ph  ph-user" style="margin-right: 0.5rem; color: var(--c-text-tertiary);"></i>
                    Người dùng
                </label>
                <select name="user_id"
                    style="width: 100%; padding: 0.75rem; border: 1px solid var(--c-gray-600); border-radius: 6px; background-color: var(--c-gray-700); color: var(--c-text-primary); font-size: 0.875rem;">
                    <option value="">Tất cả người dùng</option>
                    @foreach($filterOptions['users'] as $user)
                        <option value="{{ $user['id'] }}" {{ ($filters['user_id'] ?? '') == $user['id'] ? 'selected' : '' }}>
                            {{ $user['name'] }} ({{ $user['email'] }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Action Filter -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--c-text-primary);">
                    <i class="ph  ph-lightning" style="margin-right: 0.5rem; color: var(--c-text-tertiary);"></i>
                    Hành động
                </label>
                <select name="action"
                    style="width: 100%; padding: 0.75rem; border: 1px solid var(--c-gray-600); border-radius: 6px; background-color: var(--c-gray-700); color: var(--c-text-primary); font-size: 0.875rem;">
                    <option value="">Tất cả hành động</option>
                    @foreach($filterOptions['actions'] as $action)
                        <option value="{{ $action }}" {{ ($filters['action'] ?? '') == $action ? 'selected' : '' }}>
                            {{ $action }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date From -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--c-text-primary);">
                    <i class="ph  ph-calendar" style="margin-right: 0.5rem; color: var(--c-text-tertiary);"></i>
                    Từ ngày
                </label>
                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                    style="width: 100%; padding: 0.75rem; border: 1px solid var(--c-gray-600); border-radius: 6px; background-color: var(--c-gray-700); color: var(--c-text-primary); font-size: 0.875rem;">
            </div>

            <!-- Date To -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--c-text-primary);">
                    <i class="ph  ph-calendar-check" style="margin-right: 0.5rem; color: var(--c-text-tertiary);"></i>
                    Đến ngày
                </label>
                <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                    style="width: 100%; padding: 0.75rem; border: 1px solid var(--c-gray-600); border-radius: 6px; background-color: var(--c-gray-700); color: var(--c-text-primary); font-size: 0.875rem;">
            </div>

            <!-- Per Page -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--c-text-primary);">
                    <i class="ph  ph-list" style="margin-right: 0.5rem; color: var(--c-text-tertiary);"></i>
                    Số dòng
                </label>
                <select name="per_page"
                    style="width: 100%; padding: 0.75rem; border: 1px solid var(--c-gray-600); border-radius: 6px; background-color: var(--c-gray-700); color: var(--c-text-primary); font-size: 0.875rem;"
                    onchange="this.form.submit()">
                    <option value="20" {{ ($filters['per_page'] ?? 20) == 20 ? 'selected' : '' }}>20 dòng</option>
                    <option value="50" {{ ($filters['per_page'] ?? 20) == 50 ? 'selected' : '' }}>50 dòng</option>
                    <option value="100" {{ ($filters['per_page'] ?? 20) == 100 ? 'selected' : '' }}>100 dòng</option>
                </select>
            </div>
        </form>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" form="filterForm" class="btn-primary">
                <i class="ph  ph-funnel" style="margin-right: 0.5rem;"></i>
                Áp dụng bộ lọc
            </button>

            <!-- Quick Date Filters -->
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <button class="flat-button" onclick="setDateFilter('today')"
                    style="font-size: 0.875rem; padding: 0.5rem 1rem;">Hôm nay</button>
                <button class="flat-button" onclick="setDateFilter('yesterday')"
                    style="font-size: 0.875rem; padding: 0.5rem 1rem;">Hôm qua</button>
                <button class="flat-button" onclick="setDateFilter('this_week')"
                    style="font-size: 0.875rem; padding: 0.5rem 1rem;">Tuần này</button>
                <button class="flat-button" onclick="setDateFilter('this_month')"
                    style="font-size: 0.875rem; padding: 0.5rem 1rem;">Tháng này</button>
            </div>
        </div>

        <!-- Active Filters Display -->
        @if(!empty($currentFilters))
            <div
                style="margin-top: 1rem; padding: 1rem; background-color: rgba(69, 255, 188, 0.1); border: 1px solid var(--c-green-500); border-radius: 6px;">
                <div style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                    <i class="ph  ph-check-circle" style="margin-right: 0.5rem; color: var(--c-green-500);"></i>
                    <strong style="color: var(--c-text-primary);">Bộ lọc đang áp dụng:</strong>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    @foreach($currentFilters as $key => $value)
                        @if($value)
                            <span class="badge badge-success" style="display: flex; align-items: center;">
                                {{ ucfirst($key) }}:
                                @if($key === 'user_id')
                                    {{ $filterOptions['users']->firstWhere('id', $value)['name'] ?? $value }}
                                @else
                                    {{ $value }}
                                @endif
                                <button type="button" onclick="removeFilter('{{ $key }}')"
                                    style="margin-left: 0.5rem; background: none; border: none; color: inherit; cursor: pointer;">
                                    <i class="ph  ph-x" style="font-size: 0.75rem;"></i>
                                </button>
                            </span>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Logs Table -->
    <div class="content-section">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="margin: 0;">
                <i class="ph  ph-list-bullets" style="margin-right: 0.5rem; color: var(--c-blue-500);"></i>
                Nhật ký hoạt động
                <span style="color: var(--c-text-tertiary); font-size: 0.875rem; font-weight: normal;">
                    ({{ number_format($logs->total()) }} kết quả)
                </span>
            </h2>

            <div style="display: flex; gap: 0.75rem;">
                <button class="flat-button" onclick="refreshLogs()" title="Tải lại">
                    <i class="ph  ph-arrow-clockwise"></i>
                </button>
                <button class="flat-button" onclick="toggleAutoRefresh()" id="autoRefreshBtn" title="Tự động tải lại">
                    <i class="ph  ph-clock"></i>
                </button>
            </div>
        </div>

        @if($logs->count() > 0)
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 8%;">ID</th>
                            <th style="width: 20%;">Hành động</th>
                            <th style="width: 35%;">Mô tả</th>
                            <th style="width: 15%;">Người dùng</th>
                            <th style="width: 12%;">IP</th>
                            <th style="width: 10%;">Thời gian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr onclick="showLogDetails({{ $log->id }})" style="cursor: pointer;" title="Click để xem chi tiết">
                                <td>
                                    <span style="font-family: monospace; color: var(--c-text-tertiary);">#{{ $log->id }}</span>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        @php
                                            $actionType = explode('.', $log->action)[0] ?? 'default';
                                            $icons = [
                                                'user' => 'ph  ph-user',
                                                'article' => 'ph  ph-article',
                                                'login' => 'ph  ph-sign-in',
                                                'logout' => 'ph  ph-sign-out',
                                                'password' => 'ph  ph-lock',
                                                'profile' => 'ph  ph-user-gear',
                                                'system' => 'ph  ph-gear',
                                                'default' => 'ph  ph-activity'
                                            ];
                                            $colors = [
                                                'user' => 'var(--c-blue-500)',
                                                'article' => 'var(--c-green-500)',
                                                'login' => 'var(--c-green-500)',
                                                'logout' => 'var(--c-orange-500)',
                                                'password' => 'var(--c-red-500)',
                                                'profile' => 'var(--c-purple-500)',
                                                'system' => 'var(--c-orange-500)',
                                                'default' => 'var(--c-text-tertiary)'
                                            ];
                                        @endphp
                                        <i class="{{ $icons[$actionType] ?? $icons['default'] }}"
                                            style="margin-right: 0.5rem; font-size: 1rem; color: {{ $colors[$actionType] ?? $colors['default'] }};"></i>
                                        <span style="font-size: 0.875rem; color: var(--c-text-secondary);">{{ $log->action }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span style="color: var(--c-text-primary);">{{ Str::limit($log->description, 80) }}</span>
                                    @if($log->metadata)
                                        <i class="ph  ph-info" style="margin-left: 0.5rem; color: var(--c-text-tertiary);"
                                            title="Có metadata"></i>
                                    @endif
                                </td>
                                <td>
                                    @if($log->user)
                                        <div style="display: flex; align-items: center;">
                                            <div
                                                style="width: 24px; height: 24px; border-radius: 50%; background: linear-gradient(135deg, var(--c-green-500), var(--c-blue-500)); display: flex; align-items: center; justify-content: center; margin-right: 0.5rem; font-size: 0.625rem; font-weight: 600; color: var(--c-gray-900);">
                                                {{ strtoupper(substr($log->user->username ?: $log->user->email, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div style="font-size: 0.875rem; color: var(--c-text-primary);">
                                                    {{ $log->user->username ?: explode('@', $log->user->email)[0] }}
                                                </div>
                                                @if($log->user->username)
                                                    <div style="font-size: 0.75rem; color: var(--c-text-tertiary);">{{ $log->user->email }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span style="color: var(--c-text-tertiary); font-style: italic;">
                                            <i class="ph  ph-robot" style="margin-right: 0.25rem;"></i>
                                            System
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->ip_address)
                                        <span
                                            style="font-family: monospace; font-size: 0.875rem; color: var(--c-text-tertiary);">{{ $log->ip_address }}</span>
                                    @else
                                        <span style="color: var(--c-text-tertiary); font-style: italic;">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-size: 0.875rem; color: var(--c-text-secondary);">
                                        {{ $log->created_at->format('H:i') }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: var(--c-text-tertiary);">
                                        {{ $log->created_at->format('d/m') }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="margin-top: 1.5rem; display: flex; justify-content: between; align-items: center;">
                <div style="color: var(--c-text-tertiary); font-size: 0.875rem;">
                    Hiển thị {{ $logs->firstItem() }} - {{ $logs->lastItem() }} trong tổng số
                    {{ number_format($logs->total()) }} kết quả
                </div>

                <div style="margin-left: auto;">
                    {{ $logs->links() }}
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div style="text-align: center; padding: 4rem; color: var(--c-text-tertiary);">
                <i class="ph  ph-magnifying-glass-minus" style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                <h3 style="margin-bottom: 0.5rem; color: var(--c-text-secondary);">Không tìm thấy nhật ký</h3>
                <p style="margin-bottom: 1.5rem;">Không có nhật ký nào phù hợp với bộ lọc hiện tại.</p>
                <button class="flat-button" onclick="resetFilters()">
                    <i class="ph  ph-funnel-x" style="margin-right: 0.5rem;"></i>
                    Xóa bộ lọc
                </button>
            </div>
        @endif
    </div>
@endsection

@section('sidebar')
    <!-- Log Management Actions -->
    <section class="content-section">
        <h2>Quản lý nhật ký</h2>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <button class="btn-primary" onclick="refreshLogs()" style="text-align: center;">
                <i class="ph  ph-arrow-clockwise" style="margin-right: 0.5rem;"></i>
                Tải lại nhật ký
            </button>

            <a href="{{ route('admin.logs.export', request()->query()) }}" class="flat-button"
                style="text-align: center; background-color: var(--c-green-500); color: var(--c-gray-900);">
                <i class="ph  ph-download" style="margin-right: 0.5rem;"></i>
                Xuất CSV
            </a>

            <button class="flat-button" onclick="showCleanupModal()"
                style="text-align: center; background-color: var(--c-orange-500); color: var(--c-gray-900);">
                <i class="ph  ph-trash" style="margin-right: 0.5rem;"></i>
                Dọn dẹp logs cũ
            </button>

            <hr style="border: none; border-top: 1px solid var(--c-gray-600); margin: 0.5rem 0;">

            <a href="{{ route('admin.dashboard') }}" class="flat-button" style="text-align: center;">
                <i class="ph  ph-house" style="margin-right: 0.5rem;"></i>
                Về Dashboard
            </a>
        </div>
    </section>

    <!-- Real-time Stats -->
    <section class="content-section">
        <h2>Thống kê thời gian thực</h2>
        <div id="realtimeStats">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="color: var(--c-text-tertiary);">Logs hôm nay:</span>
                <span style="color: var(--c-text-primary); font-weight: 600;"
                    id="todayCount">{{ $stats['today_logs'] }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="color: var(--c-text-tertiary);">Tuần này:</span>
                <span style="color: var(--c-text-primary); font-weight: 600;"
                    id="weekCount">{{ $stats['this_week_logs'] }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                <span style="color: var(--c-text-tertiary);">Tổng cộng:</span>
                <span style="color: var(--c-text-primary); font-weight: 600;"
                    id="totalCount">{{ $stats['total_logs'] }}</span>
            </div>

            <div style="font-size: 0.75rem; color: var(--c-text-tertiary); text-align: center;" id="lastUpdate">
                Cập nhật lần cuối: {{ now()->format('H:i:s') }}
            </div>
        </div>
    </section>

    <!-- Filter Presets -->
    <section class="content-section">
        <h2>Bộ lọc nhanh</h2>
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            <button class="flat-button" onclick="setFilter('action', 'user.login')"
                style="text-align: left; font-size: 0.875rem;">
                <i class="ph  ph-sign-in" style="margin-right: 0.5rem; color: var(--c-green-500);"></i>
                Đăng nhập
            </button>
            <button class="flat-button" onclick="setFilter('action', 'article.created')"
                style="text-align: left; font-size: 0.875rem;">
                <i class="ph  ph-article" style="margin-right: 0.5rem; color: var(--c-blue-500);"></i>
                Tạo bài viết
            </button>
            <button class="flat-button" onclick="setFilter('action', 'user.created')"
                style="text-align: left; font-size: 0.875rem;">
                <i class="ph  ph-user-plus" style="margin-right: 0.5rem; color: var(--c-purple-500);"></i>
                Tạo người dùng
            </button>
            <button class="flat-button" onclick="setFilter('action', 'system')"
                style="text-align: left; font-size: 0.875rem;">
                <i class="ph  ph-gear" style="margin-right: 0.5rem; color: var(--c-orange-500);"></i>
                Hệ thống
            </button>
        </div>
    </section>

    <!-- Tips -->
    <section class="content-section">
        <h2>Mẹo sử dụng</h2>
        <div style="font-size: 0.875rem; color: var(--c-text-tertiary); line-height: 1.6;">
            <div style="margin-bottom: 0.75rem;">
                <i class="ph  ph-cursor-click" style="margin-right: 0.5rem; color: var(--c-green-500);"></i>
                <strong>Click vào dòng</strong> để xem chi tiết nhật ký
            </div>
            <div style="margin-bottom: 0.75rem;">
                <i class="ph  ph-funnel" style="margin-right: 0.5rem; color: var(--c-blue-500);"></i>
                <strong>Sử dụng bộ lọc</strong> để tìm kiếm chính xác
            </div>
            <div style="margin-bottom: 0.75rem;">
                <i class="ph  ph-download" style="margin-right: 0.5rem; color: var(--c-purple-500);"></i>
                <strong>Xuất CSV</strong> để phân tích offline
            </div>
            <div>
                <i class="ph  ph-trash" style="margin-right: 0.5rem; color: var(--c-orange-500);"></i>
                <strong>Dọn dẹp định kỳ</strong> để tối ưu hiệu suất
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <!-- Cleanup Modal -->
    <div id="cleanupModal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div
            style="background-color: var(--c-gray-800); border-radius: 8px; padding: 2rem; max-width: 500px; width: 90%; border: 1px solid var(--c-gray-600);">
            <h3 style="margin: 0 0 1rem 0; color: var(--c-text-primary); display: flex; align-items: center;">
                <i class="ph  ph-warning" style="margin-right: 0.5rem; color: var(--c-orange-500);"></i>
                Dọn dẹp nhật ký cũ
            </h3>
            <p style="margin-bottom: 1.5rem; color: var(--c-text-secondary);">
                Xóa tất cả nhật ký cũ hơn số ngày được chỉ định. Hành động này không thể hoàn tác!
            </p>

            <form id="cleanupForm">
                @csrf
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--c-text-primary);">
                        Xóa logs cũ hơn (ngày):
                    </label>
                    <input type="number" name="days" min="1" max="365" value="90"
                        style="width: 100%; padding: 0.75rem; border: 1px solid var(--c-gray-600); border-radius: 6px; background-color: var(--c-gray-700); color: var(--c-text-primary);"
                        required>
                    <div style="font-size: 0.75rem; color: var(--c-text-tertiary); margin-top: 0.25rem;">
                        Khuyên nên giữ ít nhất 30-90 ngày để theo dõi
                    </div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: flex; align-items: center; color: var(--c-text-primary); cursor: pointer;">
                        <input type="checkbox" name="confirm" value="1" required style="margin-right: 0.5rem;">
                        <span>Tôi hiểu rằng hành động này không thể hoàn tác</span>
                    </label>
                </div>
            </form>

            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button class="flat-button" onclick="hideCleanupModal()">
                    <i class="ph  ph-x" style="margin-right: 0.5rem;"></i>
                    Hủy bỏ
                </button>
                <button class="flat-button" onclick="performCleanup()"
                    style="background-color: var(--c-red-500); color: var(--c-white);">
                    <i class="ph  ph-trash" style="margin-right: 0.5rem;"></i>
                    Xóa logs
                </button>
            </div>
        </div>
    </div>

    <!-- Log Detail Modal -->
    <div id="logDetailModal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1001; align-items: center; justify-content: center; overflow-y: auto;">
        <div
            style="background-color: var(--c-gray-800); border-radius: 8px; padding: 2rem; max-width: 700px; width: 90%; border: 1px solid var(--c-gray-600); max-height: 90vh; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="margin: 0; color: var(--c-text-primary);">
                    <i class="ph  ph-info" style="margin-right: 0.5rem; color: var(--c-blue-500);"></i>
                    Chi tiết nhật ký
                </h3>
                <button class="icon-button" onclick="hideLogDetailModal()">
                    <i class="ph  ph-x"></i>
                </button>
            </div>
            <div id="logDetailContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        let autoRefreshInterval = null;
        let isAutoRefresh = false;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-submit form when filters change
            const form = document.getElementById('filterForm');
            const autoSubmitElements = form.querySelectorAll('select:not([name="per_page"])');

            autoSubmitElements.forEach(element => {
                element.addEventListener('change', function () {
                    // Small delay to allow user to make multiple quick changes
                    setTimeout(() => form.submit(), 300);
                });
            });

            // Enable keyboard shortcuts
            document.addEventListener('keydown', function (e) {
                // Ctrl+R to refresh
                if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
                    e.preventDefault();
                    refreshLogs();
                }

                // Ctrl+F to focus search
                if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                    e.preventDefault();
                    document.querySelector('input[name="search"]').focus();
                }

                // Escape to close modals
                if (e.key === 'Escape') {
                    hideCleanupModal();
                    hideLogDetailModal();
                }
            });

            // Add row hover effects
            const tableRows = document.querySelectorAll('.table tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function () {
                    this.style.transform = 'translateX(4px)';
                    this.style.backgroundColor = 'rgba(69, 255, 188, 0.1)';
                });

                row.addEventListener('mouseleave', function () {
                    this.style.transform = 'translateX(0)';
                    this.style.backgroundColor = '';
                });
            });

            // Initialize tooltips
            initializeTooltips();
        });

        // Filter functions
        function resetFilters() {
            window.location.href = '{{ route("admin.logs.index") }}';
        }

        function removeFilter(filterName) {
            const form = document.getElementById('filterForm');
            const input = form.querySelector(`[name="${filterName}"]`);
            if (input) {
                input.value = '';
                form.submit();
            }
        }

        function setFilter(filterName, value) {
            const form = document.getElementById('filterForm');
            const input = form.querySelector(`[name="${filterName}"]`);
            if (input) {
                input.value = value;
                form.submit();
            }
        }

        function setDateFilter(period) {
            const form = document.getElementById('filterForm');
            const dateFrom = form.querySelector('input[name="date_from"]');
            const dateTo = form.querySelector('input[name="date_to"]');
            const today = new Date();

            let fromDate, toDate;

            switch (period) {
                case 'today':
                    fromDate = toDate = today.toISOString().split('T')[0];
                    break;
                case 'yesterday':
                    const yesterday = new Date(today);
                    yesterday.setDate(today.getDate() - 1);
                    fromDate = toDate = yesterday.toISOString().split('T')[0];
                    break;
                case 'this_week':
                    const startOfWeek = new Date(today);
                    startOfWeek.setDate(today.getDate() - today.getDay());
                    fromDate = startOfWeek.toISOString().split('T')[0];
                    toDate = today.toISOString().split('T')[0];
                    break;
                case 'this_month':
                    fromDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
                    toDate = today.toISOString().split('T')[0];
                    break;
            }

            dateFrom.value = fromDate;
            dateTo.value = toDate;
            form.submit();
        }

        // Refresh functions
        function refreshLogs() {
            showLoadingState();
            window.location.reload();
        }

        function toggleAutoRefresh() {
            const btn = document.getElementById('autoRefreshBtn');

            if (isAutoRefresh) {
                // Stop auto refresh
                clearInterval(autoRefreshInterval);
                isAutoRefresh = false;
                btn.style.backgroundColor = '';
                btn.title = 'Bật tự động tải lại';
                showNotification('Đã tắt tự động tải lại', 'info');
            } else {
                // Start auto refresh (every 30 seconds)
                autoRefreshInterval = setInterval(() => {
                    updateRealtimeStats();
                }, 30000);
                isAutoRefresh = true;
                btn.style.backgroundColor = 'var(--c-green-500)';
                btn.style.color = 'var(--c-gray-900)';
                btn.title = 'Tắt tự động tải lại';
                showNotification('Đã bật tự động tải lại (30s)', 'success');
            }
        }

        // Modal functions
        function showCleanupModal() {
            document.getElementById('cleanupModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function hideCleanupModal() {
            document.getElementById('cleanupModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        function performCleanup() {
            const form = document.getElementById('cleanupForm');
            const formData = new FormData(form);

            if (!formData.get('confirm')) {
                alert('Vui lòng xác nhận việc xóa logs.');
                return;
            }

            const days = formData.get('days');
            if (!days || days < 1 || days > 365) {
                alert('Vui lòng nhập số ngày hợp lệ (1-365).');
                return;
            }

            showLoadingState();

            fetch('{{ route("admin.logs.cleanup") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    days: parseInt(days),
                    confirm: 1
                })
            })
                .then(response => response.json())
                .then(data => {
                    hideLoadingState();
                    if (data.success) {
                        showNotification(`Đã xóa ${data.deleted_count} nhật ký cũ`, 'success');
                        hideCleanupModal();
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        showNotification('Có lỗi xảy ra khi xóa logs', 'error');
                    }
                })
                .catch(error => {
                    hideLoadingState();
                    console.error('Error:', error);
                    showNotification('Có lỗi xảy ra', 'error');
                });
        }

        // Log detail functions
        function showLogDetails(logId) {
            showLoadingState();

            fetch(`{{ route('admin.logs.index') }}/${logId}`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    hideLoadingState();
                    if (data.success) {
                        displayLogDetail(data.log);
                        document.getElementById('logDetailModal').style.display = 'flex';
                        document.body.style.overflow = 'hidden';
                    } else {
                        showNotification('Không thể tải chi tiết log', 'error');
                    }
                })
                .catch(error => {
                    hideLoadingState();
                    console.error('Error:', error);
                    showNotification('Có lỗi xảy ra', 'error');
                });
        }

        function hideLogDetailModal() {
            document.getElementById('logDetailModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        function displayLogDetail(log) {
            const content = document.getElementById('logDetailContent');

            let userInfo = 'System';
            if (log.user) {
                userInfo = `
                <div style="display: flex; align-items: center;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, var(--c-green-500), var(--c-blue-500)); display: flex; align-items: center; justify-content: center; margin-right: 0.75rem; font-size: 0.875rem; font-weight: 600; color: var(--c-gray-900);">
                        ${log.user.name.substring(0, 2).toUpperCase()}
                    </div>
                    <div>
                        <div style="color: var(--c-text-primary); font-weight: 600;">${log.user.name}</div>
                        <div style="color: var(--c-text-tertiary); font-size: 0.875rem;">${log.user.email}</div>
                    </div>
                </div>
            `;
            } else {
                userInfo = `
                <div style="display: flex; align-items: center; color: var(--c-text-tertiary);">
                    <i class="ph  ph-robot" style="margin-right: 0.5rem;"></i>
                    System
                </div>
            `;
            }

            content.innerHTML = `
            <div style="display: grid; gap: 1.5rem;">
                <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem; align-items: start;">
                    <div style="color: var(--c-text-tertiary); font-weight: 600;">ID:</div>
                    <div style="color: var(--c-text-primary); font-family: monospace;">#${log.id}</div>

                    <div style="color: var(--c-text-tertiary); font-weight: 600;">Hành động:</div>
                    <div style="color: var(--c-text-primary);">${log.action}</div>

                    <div style="color: var(--c-text-tertiary); font-weight: 600;">Mô tả:</div>
                    <div style="color: var(--c-text-primary);">${log.description}</div>

                    <div style="color: var(--c-text-tertiary); font-weight: 600;">Người dùng:</div>
                    <div>${userInfo}</div>

                    <div style="color: var(--c-text-tertiary); font-weight: 600;">IP Address:</div>
                    <div style="color: var(--c-text-primary); font-family: monospace;">${log.ip_address || '-'}</div>

                    <div style="color: var(--c-text-tertiary); font-weight: 600;">Thời gian:</div>
                    <div style="color: var(--c-text-primary);">
                        ${log.created_at}<br>
                        <small style="color: var(--c-text-tertiary);">(${log.created_at_human})</small>
                    </div>

                    ${log.entity_type ? `
                    <div style="color: var(--c-text-tertiary); font-weight: 600;">Đối tượng:</div>
                    <div style="color: var(--c-text-primary);">${log.entity_type} #${log.entity_id}</div>
                    ` : ''}
                </div>

                ${log.metadata ? `
                <div>
                    <div style="color: var(--c-text-tertiary); font-weight: 600; margin-bottom: 0.5rem;">Metadata:</div>
                    <div style="background-color: var(--c-gray-700); padding: 1rem; border-radius: 6px; border: 1px solid var(--c-gray-600);">
                        <pre style="color: var(--c-text-primary); font-size: 0.875rem; margin: 0; white-space: pre-wrap; word-wrap: break-word;">${JSON.stringify(log.metadata, null, 2)}</pre>
                    </div>
                </div>
                ` : ''}
            </div>
        `;
        }

        // Utility functions
        function updateRealtimeStats() {
            fetch('{{ route("admin.logs.index") }}?ajax_stats=1', {
                headers: {
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data.stats) {
                        const stats = data.data.stats;
                        document.getElementById('todayCount').textContent = stats.today_logs.toLocaleString();
                        document.getElementById('weekCount').textContent = stats.this_week_logs.toLocaleString();
                        document.getElementById('totalCount').textContent = stats.total_logs.toLocaleString();
                        document.getElementById('lastUpdate').textContent = 'Cập nhật lần cuối: ' + new Date().toLocaleTimeString();
                    }
                })
                .catch(error => console.error('Error updating stats:', error));
        }

        function showLoadingState() {
            // Add loading overlay
            const overlay = document.createElement('div');
            overlay.id = 'loadingOverlay';
            overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        `;
            overlay.innerHTML = `
            <div style="background: var(--c-gray-800); padding: 2rem; border-radius: 8px; border: 1px solid var(--c-gray-600); text-align: center;">
                <i class="ph  ph-spinner" style="font-size: 2rem; color: var(--c-green-500); animation: spin 1s linear infinite;"></i>
                <div style="margin-top: 1rem; color: var(--c-text-primary);">Đang xử lý...</div>
            </div>
        `;
            document.body.appendChild(overlay);
        }

        function hideLoadingState() {
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                document.body.removeChild(overlay);
            }
        }

        function showNotification(message, type = 'info') {
            const colors = {
                success: 'var(--c-green-500)',
                error: 'var(--c-red-500)',
                warning: 'var(--c-orange-500)',
                info: 'var(--c-blue-500)'
            };

            const notification = document.createElement('div');
            notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${colors[type]};
            color: var(--c-gray-900);
            padding: 1rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
            z-index: 10000;
            transform: translateX(100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        `;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => notification.style.transform = 'translateX(0)', 100);
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => document.body.removeChild(notification), 300);
            }, 3000);
        }

        function initializeTooltips() {
            // Custom tooltip implementation for better UX
            const elements = document.querySelectorAll('[title]');
            elements.forEach(element => {
                let tooltip;

                element.addEventListener('mouseenter', function (e) {
                    tooltip = document.createElement('div');
                    tooltip.className = 'custom-tooltip';
                    tooltip.textContent = this.title;
                    tooltip.style.cssText = `
                    position: absolute;
                    background: var(--c-gray-700);
                    color: var(--c-text-primary);
                    padding: 0.5rem 0.75rem;
                    border-radius: 4px;
                    font-size: 0.75rem;
                    white-space: nowrap;
                    z-index: 10001;
                    pointer-events: none;
                    border: 1px solid var(--c-gray-600);
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
                    opacity: 0;
                    transform: translateY(-10px);
                    transition: all 0.2s ease;
                `;

                    document.body.appendChild(tooltip);

                    const rect = this.getBoundingClientRect();
                    tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
                    tooltip.style.top = rect.bottom + 8 + 'px';

                    setTimeout(() => {
                        tooltip.style.opacity = '1';
                        tooltip.style.transform = 'translateY(0)';
                    }, 100);

                    this.removeAttribute('title');
                    this.setAttribute('data-original-title', tooltip.textContent);
                });

                element.addEventListener('mouseleave', function () {
                    if (tooltip) {
                        tooltip.style.opacity = '0';
                        tooltip.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            if (tooltip && tooltip.parentNode) {
                                document.body.removeChild(tooltip);
                            }
                        }, 200);
                    }

                    const originalTitle = this.getAttribute('data-original-title');
                    if (originalTitle) {
                        this.setAttribute('title', originalTitle);
                        this.removeAttribute('data-original-title');
                    }
                });
            });
        }

        // Add spinner animation
        const style = document.createElement('style');
        style.textContent = `
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
        document.head.appendChild(style);
    </script>
@endpush
