@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-description', 'Tổng quan hoạt động hệ thống')

@section('content')
    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>{{ $stats['total_articles'] ?? 0 }}</h3>
            <p>Tổng bài viết</p>
        </div>
        <div class="stat-card">
            <h3>{{ $stats['published_articles'] ?? 0 }}</h3>
            <p>Đã xuất bản</p>
        </div>
        <div class="stat-card">
            <h3>{{ $stats['draft_articles'] ?? 0 }}</h3>
            <p>Bản nháp</p>
        </div>
        @if(auth()->user()->isAdmin())
            <div class="stat-card">
                <h3>{{ $stats['total_users'] ?? 0 }}</h3>
                <p>Người dùng</p>
            </div>
        @endif
        @if(isset($stats['featured_articles']))
            <div class="stat-card">
                <h3>{{ $stats['featured_articles'] }}</h3>
                <p>Bài nổi bật</p>
            </div>
        @endif
    </div>

    <!-- Recent Articles -->
    <div class="content-section">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h2 style="margin: 0;">Bài viết gần đây</h2>
            @if(auth()->user()->hasPermission('articles.view'))
                <a href="{{ route('admin.articles.index') }}" class="flat-button">
                    <i class="ph  ph-eye" style="margin-right: 0.5rem;"></i>
                    Xem tất cả
                </a>
            @endif
        </div>

        @if($recentArticles && $recentArticles->count() > 0)
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Tiêu đề</th>
                            <th style="width: 15%;">Trạng thái</th>
                            <th style="width: 20%;">Tác giả</th>
                            <th style="width: 15%;">Ngày tạo</th>
                            <th style="width: 10%;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentArticles as $article)
                            <tr>
                                <td>
                                    <div style="display: flex; flex-direction: column;">
                                        <strong style="color: var(--c-text-primary); margin-bottom: 0.25rem;">
                                            {{ Str::limit($article['title'], 60) }}
                                        </strong>
                                        <span style="font-size: 0.75rem; color: var(--c-text-tertiary);">
                                            ID: #{{ $article['id'] }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    @if($article['status'] === 'published')
                                        <span class="badge badge-success">
                                            <i class="ph  ph-check-circle" style="margin-right: 0.25rem;"></i>
                                            Đã xuất bản
                                        </span>
                                    @elseif($article['status'] === 'draft')
                                        <span class="badge badge-secondary">
                                            <i class="ph  ph-file-text" style="margin-right: 0.25rem;"></i>
                                            Bản nháp
                                        </span>
                                    @else
                                        <span class="badge badge-warning">
                                            <i class="ph  ph-archive" style="margin-right: 0.25rem;"></i>
                                            Lưu trữ
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <div
                                            style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, var(--c-green-500), var(--c-blue-500)); display: flex; align-items: center; justify-content: center; margin-right: 0.5rem; font-size: 0.75rem; font-weight: 600; color: var(--c-gray-900);">
                                            {{ strtoupper(substr($article['author'], 0, 2)) }}
                                        </div>
                                        <span style="color: var(--c-text-secondary);">{{ $article['author'] }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        style="color: var(--c-text-tertiary); font-size: 0.875rem;">{{ $article['created_at'] }}</span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="#" class="icon-button" title="Xem">
                                            <i class="ph  ph-eye"></i>
                                        </a>
                                        <a href="#" class="icon-button" title="Chỉnh sửa">
                                            <i class="ph  ph-pencil"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 3rem; color: var(--c-text-tertiary);">
                <i class="ph  ph-file-plus" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                <h3 style="margin-bottom: 0.5rem; color: var(--c-text-secondary);">Chưa có bài viết nào</h3>
                <p style="margin-bottom: 1.5rem;">Hãy bắt đầu tạo bài viết đầu tiên cho website của bạn</p>
                @if(auth()->user()->hasPermission('articles.create') ?? true)
                <a href="{{ route('admin.articles.create') }}" class="btn-primary">
                    <i class="ph  ph-plus" style="margin-right: 0.5rem;"></i>
                    Tạo bài viết đầu tiên
                </a>
                @endif
            </div>
        @endif
    </div>

    @if(auth()->user()->isEditor() && !auth()->user()->isAdmin())
        <!-- My Statistics for Editor -->
        <div class="content-section">
            <h2>Thống kê của tôi</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>{{ $stats['my_articles'] ?? 0 }}</h3>
                    <p>Bài viết của tôi</p>
                </div>
                <div class="stat-card">
                    <h3>{{ $stats['my_published'] ?? 0 }}</h3>
                    <p>Đã xuất bản</p>
                </div>
                <div class="stat-card">
                    <h3>{{ $stats['my_drafts'] ?? 0 }}</h3>
                    <p>Bản nháp</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Welcome Message -->
    @if(auth()->user()->last_login_at === null)
        <div class="content-section"
            style="background: linear-gradient(135deg, rgba(69, 255, 188, 0.1), rgba(79, 172, 254, 0.1)); border-color: var(--c-green-500);">
            <div style="display: flex; align-items: center;">
                <i class="ph  ph-hand-waving" style="font-size: 2rem; margin-right: 1rem; color: var(--c-green-500);"></i>
                <div>
                    <h2 style="margin: 0 0 0.5rem 0;">Chào mừng bạn đến với News Admin!</h2>
                    <p style="margin: 0; color: var(--c-text-secondary);">
                        Đây là lần đầu bạn đăng nhập. Hãy khám phá các tính năng quản lý tin tức mạnh mẽ của chúng tôi.
                    </p>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('sidebar')
    <!-- Quick Actions -->
    <section class="content-section">
        <h2>Thao tác nhanh</h2>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @if(auth()->user()->hasPermission('articles.create') ?? true)
                <a href="{{ route('admin.articles.create') }}" class="btn-primary" style="text-align: center;">
                    <i class="ph  ph-plus" style="margin-right: 0.5rem;"></i>
                    Tạo bài viết mới
                </a>
            @endif

            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.users.create') }}" class="flat-button" style="text-align: center;">
                    <i class="ph  ph-user-plus" style="margin-right: 0.5rem;"></i>
                    Thêm người dùng
                </a>
            @endif

            <a href="{{ route('admin.profile.show') }}" class="flat-button" style="text-align: center;">
                <i class="ph  ph-gear" style="margin-right: 0.5rem;"></i>
                Cài đặt tài khoản
            </a>

            <hr style="border: none; border-top: 1px solid var(--c-gray-600); margin: 0.5rem 0;">

            <a href="#" class="flat-button" style="text-align: center; color: var(--c-text-tertiary);">
                <i class="ph  ph-question" style="margin-right: 0.5rem;"></i>
                Trợ giúp
            </a>
        </div>
    </section>

    @if(auth()->user()->isAdmin() && isset($recentLogs) && $recentLogs->count() > 0)
        <!-- Recent Activity for Admin -->
        <section class="content-section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h2 style="margin: 0;">Hoạt động gần đây</h2>
                <a href="{{ route('admin.logs.index') }}" class="icon-button" title="Xem tất cả">
                    <i class="ph  ph-arrow-right"></i>
                </a>
            </div>

            <div style="max-height: 400px; overflow-y: auto;">
                @foreach($recentLogs as $log)
                    <div style="display: flex; align-items: start; padding: 0.75rem 0; border-bottom: 1px solid var(--c-gray-600);">
                        <div
                            style="width: 32px; height: 32px; border-radius: 50%; background-color: var(--c-gray-600); display: flex; align-items: center; justify-content: center; margin-right: 0.75rem; flex-shrink: 0;">
                            @if(str_contains($log['action'], 'login'))
                                <i class="ph  ph-sign-in" style="font-size: 0.875rem; color: var(--c-green-500);"></i>
                            @elseif(str_contains($log['action'], 'article'))
                                <i class="ph  ph-article" style="font-size: 0.875rem; color: var(--c-blue-500);"></i>
                            @elseif(str_contains($log['action'], 'user'))
                                <i class="ph  ph-user" style="font-size: 0.875rem; color: var(--c-purple-500);"></i>
                            @else
                                <i class="ph  ph-activity" style="font-size: 0.875rem; color: var(--c-text-tertiary);"></i>
                            @endif
                        </div>
                        <div style="flex: 1;">
                            <div style="font-size: 0.875rem; color: var(--c-text-secondary); margin-bottom: 0.25rem;">
                                {{ $log['description'] }}
                            </div>
                            <div style="font-size: 0.75rem; color: var(--c-text-tertiary);">
                                <i class="ph  ph-user" style="margin-right: 0.25rem;"></i>
                                {{ $log['user'] }}
                                <span style="margin: 0 0.5rem;">•</span>
                                <i class="ph  ph-clock" style="margin-right: 0.25rem;"></i>
                                {{ $log['created_at'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- System Info -->
    <section class="content-section">
        <h2>Thông tin hệ thống</h2>
        <div style="font-size: 0.875rem;">
            <div
                style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid var(--c-gray-600);">
                <span style="color: var(--c-text-tertiary);">
                    <i class="ph  ph-info" style="margin-right: 0.5rem;"></i>
                    Phiên bản:
                </span>
                <span style="color: var(--c-text-primary); font-weight: 600;">v1.0.0</span>
            </div>
            <div
                style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid var(--c-gray-600);">
                <span style="color: var(--c-text-tertiary);">
                    <i class="ph  ph-code" style="margin-right: 0.5rem;"></i>
                    Laravel:
                </span>
                <span style="color: var(--c-text-primary);">{{ app()->version() }}</span>
            </div>
            <div
                style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid var(--c-gray-600);">
                <span style="color: var(--c-text-tertiary);">
                    <i class="ph  ph-code" style="margin-right: 0.5rem;"></i>
                    PHP:
                </span>
                <span style="color: var(--c-text-primary);">{{ PHP_VERSION }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0;">
                <span style="color: var(--c-text-tertiary);">
                    <i class="ph  ph-clock" style="margin-right: 0.5rem;"></i>
                    Đăng nhập cuối:
                </span>
                <span style="color: var(--c-text-primary);">{{ $user['last_login_at'] ?? 'Lần đầu' }}</span>
            </div>
        </div>
    </section>

    <!-- User Info -->
    <section class="content-section">
        <h2>Thông tin tài khoản</h2>
        <div style="text-align: center; padding: 1rem 0;">
            <div
                style="width: 64px; height: 64px; border-radius: 50%; background: linear-gradient(135deg, var(--c-green-500), var(--c-blue-500)); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.5rem; font-weight: 600; color: var(--c-gray-900);">
                {{ strtoupper(substr($user['display_name'], 0, 2)) }}
            </div>
            <h3 style="margin: 0 0 0.5rem 0; color: var(--c-text-primary);">{{ $user['display_name'] }}</h3>
            <p style="margin: 0 0 1rem 0; color: var(--c-text-tertiary); font-size: 0.875rem;">{{ $user['email'] }}</p>

            <div style="display: flex; justify-content: center; gap: 0.5rem; margin-bottom: 1rem;">
                @foreach($user['roles'] as $role)
                    <span class="badge badge-success">{{ $role }}</span>
                @endforeach
            </div>

            <div style="display: flex; justify-content: space-around; text-align: center; font-size: 0.875rem;">
                <div>
                    <div style="font-weight: 600; color: var(--c-text-primary);">
                        @if(auth()->user()->isEditor())
                            {{ $stats['my_articles'] ?? 0 }}
                        @else
                            {{ $stats['total_articles'] ?? 0 }}
                        @endif
                    </div>
                    <div style="color: var(--c-text-tertiary);">Bài viết</div>
                </div>
                @if(auth()->user()->isAdmin())
                    <div>
                        <div style="font-weight: 600; color: var(--c-text-primary);">{{ $stats['total_users'] ?? 0 }}</div>
                        <div style="color: var(--c-text-tertiary);">Người dùng</div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Add loading states to quick action buttons
            const quickActionButtons = document.querySelectorAll('.btn-primary, .flat-button');
            quickActionButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    if (this.href === '#' || this.href.includes('#')) {
                        e.preventDefault();
                        return;
                    }

                    showLoading(this);
                });
            });

            // Animate stats cards on load
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Add hover effects to table rows
            const tableRows = document.querySelectorAll('.table tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function () {
                    this.style.transform = 'translateX(4px)';
                });

                row.addEventListener('mouseleave', function () {
                    this.style.transform = 'translateX(0)';
                });
            });

            // Auto refresh activity logs every 30 seconds (if admin)
            @if(auth()->user()->isAdmin())
                setInterval(() => {
                    // This would be replaced with an AJAX call to refresh logs
                    console.log('Refreshing activity logs...');
                }, 30000);
            @endif
        });
    </script>
@endpush
