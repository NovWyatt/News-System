@extends('layouts.admin')

@section('title', $article->title)
@section('page-description', 'Chi tiết bài viết và thông tin metadata')

@push('styles')
<style>
    /* Article header */
    .article-header {
        background: linear-gradient(135deg, var(--c-gray-700) 0%, var(--c-gray-800) 100%);
        border-radius: 12px;
        padding: 2rem;
        border: 1px solid var(--c-gray-600);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .article-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--c-green-500), var(--c-blue-500));
    }

    .article-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        color: var(--c-text-tertiary);
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
        background-color: var(--c-gray-600);
        border-radius: 20px;
    }

    .article-title {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1.2;
        margin: 0 0 1rem 0;
        background: linear-gradient(135deg, var(--c-green-400), var(--c-blue-400));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .article-slug {
        font-family: 'Monaco', 'Consolas', 'Courier New', monospace;
        font-size: 0.875rem;
        color: var(--c-text-tertiary);
        background-color: var(--c-gray-600);
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        display: inline-block;
        margin-bottom: 1rem;
    }

    /* Status badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .status-badge.published {
        background-color: rgba(34, 197, 94, 0.15);
        color: var(--c-green-400);
        border: 1px solid var(--c-green-500);
    }

    .status-badge.draft {
        background-color: rgba(251, 146, 60, 0.15);
        color: var(--c-orange-400);
        border: 1px solid var(--c-orange-500);
    }

    .status-badge.archived {
        background-color: rgba(156, 163, 175, 0.15);
        color: var(--c-gray-400);
        border: 1px solid var(--c-gray-500);
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .status-dot.published {
        background-color: var(--c-green-400);
    }

    .status-dot.draft {
        background-color: var(--c-orange-400);
    }

    .status-dot.archived {
        background-color: var(--c-gray-400);
    }

    /* Actions bar */
    .actions-bar {
        position: sticky;
        top: 0;
        background-color: var(--c-gray-800);
        padding: 1rem 2rem;
        border-bottom: 1px solid var(--c-gray-600);
        z-index: 100;
        margin: -2rem -2rem 2rem -2rem;
        border-radius: 8px 8px 0 0;
    }

    .actions-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .actions-left,
    .actions-right {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }

    /* Button styles */
    .btn-secondary {
        background-color: var(--c-gray-600);
        color: var(--c-text-secondary);
        border: 1px solid var(--c-gray-500);
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.25s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .btn-secondary:hover {
        background-color: var(--c-gray-500);
        color: var(--c-text-primary);
        transform: translateY(-1px);
    }

    .btn-outline {
        background: transparent;
        color: var(--c-text-secondary);
        border: 1px solid var(--c-gray-500);
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
    }

    .btn-outline:hover {
        background-color: var(--c-gray-600);
        color: var(--c-text-primary);
        transform: translateY(-1px);
    }

    .btn-warning {
        background-color: var(--c-orange-500);
        color: var(--c-gray-900);
        border: 1px solid var(--c-orange-400);
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .btn-warning:hover {
        background-color: var(--c-orange-400);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(251, 146, 60, 0.3);
    }

    .btn-danger {
        background-color: var(--c-red-500);
        color: var(--c-white);
        border: 1px solid var(--c-red-400);
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .btn-danger:hover {
        background-color: var(--c-red-400);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    /* Featured indicator */
    .featured-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: linear-gradient(135deg, var(--c-orange-500), var(--c-red-500));
        color: var(--c-white);
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        box-shadow: 0 4px 12px rgba(251, 146, 60, 0.3);
    }

    /* Content sections */
    .content-section {
        background-color: var(--c-gray-700);
        border-radius: 8px;
        padding: 2rem;
        border: 1px solid var(--c-gray-600);
        margin-bottom: 2rem;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--c-gray-600);
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--c-text-primary);
        margin: 0;
    }

    .section-icon {
        font-size: 1.5rem;
    }

    /* Article content */
    .article-content {
        line-height: 1.8;
        color: var(--c-text-secondary);
        font-size: 1rem;
    }

    .article-content h1,
    .article-content h2,
    .article-content h3,
    .article-content h4,
    .article-content h5,
    .article-content h6 {
        color: var(--c-text-primary);
        margin: 2rem 0 1rem 0;
        font-weight: 600;
    }

    .article-content h1 {
        font-size: 2rem;
        border-bottom: 2px solid var(--c-gray-600);
        padding-bottom: 0.5rem;
    }

    .article-content h2 {
        font-size: 1.75rem;
    }

    .article-content h3 {
        font-size: 1.5rem;
    }

    .article-content p {
        margin-bottom: 1.25rem;
    }

    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1.5rem 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .article-content blockquote {
        border-left: 4px solid var(--c-green-500);
        background-color: var(--c-gray-600);
        padding: 1rem 1.5rem;
        margin: 1.5rem 0;
        border-radius: 0 6px 6px 0;
        font-style: italic;
    }

    .article-content code {
        background-color: var(--c-gray-600);
        color: var(--c-green-400);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-family: 'Monaco', 'Consolas', 'Courier New', monospace;
        font-size: 0.875rem;
    }

    .article-content pre {
        background-color: var(--c-gray-800);
        padding: 1.5rem;
        border-radius: 8px;
        overflow-x: auto;
        margin: 1.5rem 0;
        border: 1px solid var(--c-gray-600);
    }

    .article-content pre code {
        background: none;
        padding: 0;
        color: var(--c-text-secondary);
    }

    .article-content ul,
    .article-content ol {
        margin: 1rem 0;
        padding-left: 2rem;
    }

    .article-content li {
        margin-bottom: 0.5rem;
    }

    /* Statistics grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background-color: var(--c-gray-700);
        padding: 1.5rem;
        border-radius: 8px;
        border: 1px solid var(--c-gray-600);
        text-align: center;
        transition: 0.25s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--c-green-500), var(--c-blue-500));
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border-color: var(--c-gray-500);
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--c-green-400), var(--c-blue-400));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
        display: block;
    }

    .stat-label {
        color: var(--c-text-tertiary);
        font-size: 0.875rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .stat-icon {
        font-size: 3rem;
        color: var(--c-text-tertiary);
        opacity: 0.3;
        position: absolute;
        top: 1rem;
        right: 1rem;
    }

    /* Featured image */
    .featured-image-container {
        position: relative;
        margin-bottom: 2rem;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .featured-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        display: block;
    }

    .image-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.7));
        padding: 2rem;
        color: var(--c-white);
    }

    .no-image {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 200px;
        background-color: var(--c-gray-600);
        border-radius: 8px;
        border: 2px dashed var(--c-gray-500);
        color: var(--c-text-tertiary);
        margin-bottom: 2rem;
    }

    .no-image i {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    /* Author info */
    .author-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem;
        background-color: var(--c-gray-600);
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .author-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--c-green-500), var(--c-blue-500));
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.5rem;
        color: var(--c-gray-900);
        flex-shrink: 0;
    }

    .author-details h4 {
        margin: 0 0 0.25rem 0;
        color: var(--c-text-primary);
        font-size: 1.125rem;
    }

    .author-details p {
        margin: 0;
        color: var(--c-text-tertiary);
        font-size: 0.875rem;
    }

    /* Action buttons in content */
    .quick-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .quick-btn {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        cursor: pointer;
        transition: 0.25s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .quick-btn.featured {
        background-color: var(--c-orange-500);
        color: var(--c-gray-900);
    }

    .quick-btn.featured:hover {
        background-color: var(--c-orange-400);
        transform: translateY(-1px);
    }

    .quick-btn.not-featured {
        background-color: var(--c-gray-600);
        color: var(--c-text-secondary);
    }

    .quick-btn.not-featured:hover {
        background-color: var(--c-gray-500);
        color: var(--c-text-primary);
    }

    /* Table styling for metadata */
    .metadata-table {
        width: 100%;
        border-collapse: collapse;
    }

    .metadata-table th,
    .metadata-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--c-gray-600);
    }

    .metadata-table th {
        background-color: var(--c-gray-600);
        font-weight: 600;
        color: var(--c-text-primary);
        width: 200px;
    }

    .metadata-table td {
        color: var(--c-text-secondary);
    }

    .metadata-table tr:hover {
        background-color: var(--c-gray-600);
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .article-title {
            font-size: 2rem;
        }

        .actions-row {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }

        .actions-left,
        .actions-right {
            justify-content: center;
        }

        .stats-grid {
            grid-template-columns: 1fr 1fr;
        }

        .article-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .content-section {
            padding: 1.5rem;
        }

        .featured-image {
            height: 250px;
        }

        .metadata-table {
            font-size: 0.875rem;
        }

        .metadata-table th,
        .metadata-table td {
            padding: 0.75rem 0.5rem;
        }
    }

</style>
@endpush

@section('content')
<!-- Actions Bar -->
<div class="actions-bar">
    <div class="actions-row">
        <div class="actions-left">
            <a href="{{ route('admin.articles.index') }}" class="btn-secondary">
                <i class="ph ph-arrow-left"></i>
                Quay lại danh sách
            </a>
            <div class="status-badge {{ $article->status }}">
                <span class="status-dot {{ $article->status }}"></span>
                @if($article->status === 'published')
                Đã xuất bản
                @elseif($article->status === 'draft')
                Bản nháp
                @else
                Lưu trữ
                @endif
            </div>
        </div>
        <div class="actions-right">
            @if($article->canEdit())
            <a href="{{ route('admin.articles.edit', $article) }}" class="btn-outline">
                <i class="ph ph-pencil-simple"></i>
                Chỉnh sửa
            </a>
            @endif

            @if($article->status === 'draft' && auth()->user()->hasPermission('articles.publish'))
            <form method="POST" action="{{ route('admin.articles.publish', $article) }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn-primary" onclick="return confirm('Bạn có chắc muốn xuất bản bài viết này?')">
                    <i class="ph ph-upload-simple"></i>
                    Xuất bản
                </button>
            </form>
            @elseif($article->status === 'published' && auth()->user()->hasPermission('articles.publish'))
            <form method="POST" action="{{ route('admin.articles.unpublish', $article) }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn-warning" onclick="return confirm('Bạn có chắc muốn hủy xuất bản bài viết này?')">
                    <i class="ph ph-download-simple"></i>
                    Hủy xuất bản
                </button>
            </form>
            @endif

            @if($article->canEdit())
            <form method="POST" action="{{ route('admin.articles.toggle-featured', $article) }}" style="display: inline;">
                @csrf
                <button type="submit" class="quick-btn {{ $article->is_featured ? 'featured' : 'not-featured' }}" onclick="return confirm('Bạn có muốn thay đổi trạng thái nổi bật của bài viết này?')">
                    <i class="ph {{ $article->is_featured ? 'ph-star-fill' : 'ph-star' }}"></i>
                    {{ $article->is_featured ? 'Bỏ nổi bật' : 'Đánh dấu nổi bật' }}
                </button>
            </form>
            @endif

            @if(auth()->user()->hasPermission('articles.delete') && $article->canEdit())
            <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger" onclick="return confirm('Bạn có chắc muốn xóa bài viết này? Hành động này không thể hoàn tác!')">
                    <i class="ph ph-trash"></i>
                    Xóa bài viết
                </button>
            </form>
            @endif
        </div>
    </div>
</div>

<!-- Article Header -->
<div class="article-header">
    @if($article->is_featured)
    <div class="featured-badge">
        <i class="ph ph-star-fill"></i>
        Nổi bật
    </div>
    @endif

    <div class="article-meta">
        <div class="meta-item">
            <i class="ph ph-user"></i>
            {{ $article->author->username ?: $article->author->display_name }}
        </div>
        <div class="meta-item">
            <i class="ph ph-calendar"></i>
            {{ $article->created_at->format('d/m/Y H:i') }}
        </div>
        @if($article->published_at)
        <div class="meta-item">
            <i class="ph ph-globe"></i>
            Xuất bản {{ $article->published_at->format('d/m/Y H:i') }}
        </div>
        @endif
        <div class="meta-item">
            <i class="ph ph-clock"></i>
            {{ $article->reading_time }} phút đọc
        </div>
        @if($article->updated_at != $article->created_at)
        <div class="meta-item">
            <i class="ph ph-pencil-simple"></i>
            Sửa {{ $article->updated_at->format('d/m/Y H:i') }}
        </div>
        @endif
    </div>

    <h1 class="article-title">{{ $article->title }}</h1>

    <div class="article-slug">
        <i class="ph ph-link" style="margin-right: 0.5rem;"></i>
        {{ url('/') }}/{{ $article->slug }}
    </div>
</div>

<!-- Article Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <i class="ph ph-text-aa stat-icon"></i>
        <div class="stat-value">{{ str_word_count(strip_tags($article->content)) }}</div>
        <div class="stat-label">Số từ</div>
    </div>
    <div class="stat-card">
        <i class="ph ph-text-a-underline stat-icon"></i>
        <div class="stat-value">{{ strlen($article->content) }}</div>
        <div class="stat-label">Ký tự</div>
    </div>
    <div class="stat-card">
        <i class="ph ph-clock stat-icon"></i>
        <div class="stat-value">{{ $article->reading_time }}</div>
        <div class="stat-label">Phút đọc</div>
    </div>
    <div class="stat-card">
        <i class="ph ph-eye stat-icon"></i>
        <div class="stat-value">{{ $article->status === 'published' ? 'Công khai' : 'Riêng tư' }}</div>
        <div class="stat-label">Trạng thái</div>
    </div>
</div>

<!-- Alerts -->
@if(session('success'))
<div class="alert alert-success">
    <i class="ph ph-check-circle" style="margin-right: 0.5rem;"></i>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-error">
    <i class="ph ph-x-circle" style="margin-right: 0.5rem;"></i>
    {{ session('error') }}
</div>
@endif

<!-- Featured Image -->
@if($article->featured_image)
<div class="featured-image-container">
    <img src="{{ $article->featured_image }}" alt="{{ $article->title }}" class="featured-image">
    <div class="image-overlay">
        <p style="margin: 0; opacity: 0.8; font-size: 0.875rem;">Hình ảnh đại diện</p>
    </div>
</div>
@else
<div class="no-image">
    <i class="ph ph-image"></i>
    <p>Bài viết này chưa có hình ảnh đại diện</p>
</div>
@endif

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Article Content -->
    <div>
        <div class="content-section">
            <div class="section-header">
                <i class="ph ph-article section-icon" style="color: var(--c-green-500);"></i>
                <h2 class="section-title">Nội dung bài viết</h2>
            </div>
            <div class="article-content">
                {!! nl2br(e($article->content)) !!}
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Author Information -->
        <div class="content-section">
            <div class="section-header">
                <i class="ph ph-user section-icon" style="color: var(--c-blue-500);"></i>
                <h2 class="section-title">Tác giả</h2>
            </div>
            <div class="author-info" style="background: transparent; padding: 0; margin: 0;">
                <div class="author-avatar">
                    {{ strtoupper(substr($article->author->username ?: $article->author->email, 0, 2)) }}
                </div>
                <div class="author-details">
                    <h4>{{ $article->author->username ?: $article->author->display_name }}</h4>
                    <p>{{ $article->author->email }}</p>
                    @if($article->author->isAdmin())
                    <span style="background-color: var(--c-red-500); color: var(--c-white); padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.6rem; text-transform: uppercase; font-weight: 600;">Admin</span>
                    @elseif($article->author->isEditor())
                    <span style="background-color: var(--c-blue-500); color: var(--c-white); padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.6rem; text-transform: uppercase; font-weight: 600;">Editor</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Article Metadata -->
        <div class="content-section">
            <div class="section-header">
                <i class="ph ph-info section-icon" style="color: var(--c-purple-500);"></i>
                <h2 class="section-title">Thông tin chi tiết</h2>
            </div>
            <table class="metadata-table">
                <tr>
                    <th>ID</th>
                    <td>#{{ $article->id }}</td>
                </tr>
                <tr>
                    <th>Slug</th>
                    <td>
                        <code style="cursor: pointer; transition: 0.25s ease;" title="Click để copy" onclick="copySlug()">{{ $article->slug }}</code>
                    </td>
                </tr>
                <tr>
                    <th>Trạng thái</th>
                    <td>
                        <div class="status-badge {{ $article->status }}" style="display: inline-flex; padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                            <span class="status-dot {{ $article->status }}"></span>
                            @if($article->status === 'published')
                            Đã xuất bản
                            @elseif($article->status === 'draft')
                            Bản nháp
                            @else
                            Lưu trữ
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Nổi bật</th>
                    <td>
                        @if($article->is_featured)
                        <span style="color: var(--c-orange-400);">
                            <i class="ph ph-star-fill"></i> Có
                        </span>
                        @else
                        <span style="color: var(--c-text-tertiary);">
                            <i class="ph ph-star"></i> Không
                        </span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Ngày tạo</th>
                    <td>
                        {{ $article->created_at->format('d/m/Y H:i:s') }}
                        <br>
                        <small style="color: var(--c-text-tertiary);">{{ $article->created_at->diffForHumans() }}</small>
                    </td>
                </tr>
                @if($article->published_at)
                <tr>
                    <th>Ngày xuất bản</th>
                    <td>
                        {{ $article->published_at->format('d/m/Y H:i:s') }}
                        <br>
                        <small style="color: var(--c-text-tertiary);">{{ $article->published_at->diffForHumans() }}</small>
                    </td>
                </tr>
                @endif
                @if($article->updated_at != $article->created_at)
                <tr>
                    <th>Lần sửa cuối</th>
                    <td>
                        {{ $article->updated_at->format('d/m/Y H:i:s') }}
                        <br>
                        <small style="color: var(--c-text-tertiary);">{{ $article->updated_at->diffForHumans() }}</small>
                    </td>
                </tr>
                @endif
                <tr>
                    <th>URL Preview</th>
                    <td>
                        <a href="{{ url('/' . $article->slug) }}" target="_blank" style="color: var(--c-green-400); text-decoration: none; word-break: break-all;">
                            {{ url('/' . $article->slug) }}
                            <i class="ph ph-arrow-square-out" style="margin-left: 0.25rem; font-size: 0.75rem;"></i>
                        </a>
                        <button onclick="copyUrl()" style="background: none; border: none; color: var(--c-text-tertiary); cursor: pointer; padding: 0.25rem; margin-left: 0.5rem; border-radius: 4px; transition: 0.25s ease;" title="Copy URL">
                            <i class="ph ph-copy"></i>
                        </button>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Quick Actions -->
        @if($article->canEdit())
        <div class="content-section">
            <div class="section-header">
                <i class="ph ph-lightning section-icon" style="color: var(--c-orange-500);"></i>
                <h2 class="section-title">Thao tác nhanh</h2>
            </div>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <a href="{{ route('admin.articles.edit', $article) }}" class="btn-outline" style="justify-content: center;">
                    <i class="ph ph-pencil-simple"></i>
                    Chỉnh sửa bài viết
                </a>

                @if($article->status === 'draft' && auth()->user()->hasPermission('articles.publish'))
                <form method="POST" action="{{ route('admin.articles.publish', $article) }}">
                    @csrf
                    <button type="submit" class="btn-primary" style="width: 100%; justify-content: center;" onclick="return confirm('Bạn có chắc muốn xuất bản bài viết này?')">
                        <i class="ph ph-upload-simple"></i>
                        Xuất bản ngay
                    </button>
                </form>
                @elseif($article->status === 'published' && auth()->user()->hasPermission('articles.publish'))
                <form method="POST" action="{{ route('admin.articles.unpublish', $article) }}">
                    @csrf
                    <button type="submit" class="btn-warning" style="width: 100%; justify-content: center;" onclick="return confirm('Bạn có chắc muốn hủy xuất bản bài viết này?')">
                        <i class="ph ph-download-simple"></i>
                        Hủy xuất bản
                    </button>
                </form>
                @endif

                <form method="POST" action="{{ route('admin.articles.toggle-featured', $article) }}">
                    @csrf
                    <button type="submit" class="quick-btn {{ $article->is_featured ? 'featured' : 'not-featured' }}" style="width: 100%; justify-content: center;" onclick="return confirm('Bạn có muốn thay đổi trạng thái nổi bật của bài viết này?')">
                        <i class="ph {{ $article->is_featured ? 'ph-star-fill' : 'ph-star' }}"></i>
                        {{ $article->is_featured ? 'Bỏ nổi bật' : 'Đánh dấu nổi bật' }}
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.articles.archive', $article) }}">
                    @csrf
                    <button type="submit" class="btn-outline" style="width: 100%; justify-content: center; border-color: var(--c-orange-500); color: var(--c-orange-400);" onclick="return confirm('Bạn có chắc muốn lưu trữ bài viết này?')">
                        <i class="ph ph-archive"></i>
                        {{ $article->status === 'archived' ? 'Bỏ lưu trữ' : 'Lưu trữ' }}
                    </button>
                </form>

                @if(auth()->user()->hasPermission('articles.delete'))
                <form method="POST" action="{{ route('admin.articles.destroy', $article) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger" style="width: 100%; justify-content: center;" onclick="return confirm('Bạn có chắc muốn xóa bài viết này? Hành động này không thể hoàn tác!')">
                        <i class="ph ph-trash"></i>
                        Xóa vĩnh viễn
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endif

        <!-- SEO Preview -->
        <div class="content-section">
            <div class="section-header">
                <i class="ph ph-magnifying-glass section-icon" style="color: var(--c-green-500);"></i>
                <h2 class="section-title">SEO Preview</h2>
            </div>
            <div style="border: 1px solid var(--c-gray-600); border-radius: 8px; padding: 1rem; background-color: var(--c-gray-600);">
                <div style="color: var(--c-green-400); font-size: 0.875rem; margin-bottom: 0.25rem; word-break: break-all;">
                    {{ url('/' . $article->slug) }}
                </div>
                <div style="color: var(--c-blue-400); font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem; line-height: 1.3;">
                    {{ $article->title }}
                </div>
                <div style="color: var(--c-text-secondary); font-size: 0.875rem; line-height: 1.4;">
                    {{ Str::limit(strip_tags($article->content), 150) }}
                </div>
                <div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid var(--c-gray-500); font-size: 0.75rem; color: var(--c-text-tertiary);">
                    <i class="ph ph-calendar" style="margin-right: 0.25rem;"></i>
                    {{ $article->published_at ? $article->published_at->format('d/m/Y') : $article->created_at->format('d/m/Y') }}
                    <span style="margin: 0 0.5rem;">•</span>
                    <i class="ph ph-clock" style="margin-right: 0.25rem;"></i>
                    {{ $article->reading_time }} phút đọc
                    <button onclick="copyUrl()" style="float: right; background: none; border: none; color: var(--c-text-tertiary); cursor: pointer; padding: 0.25rem; transition: 0.25s ease;" onmouseenter="this.style.color='var(--c-green-400)'" onmouseleave="this.style.color='var(--c-text-tertiary)'" title="Copy URL">
                        <i class="ph ph-copy"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Article Analytics (Placeholder) -->
        <div class="content-section">
            <div class="section-header">
                <i class="ph ph-chart-line section-icon" style="color: var(--c-blue-500);"></i>
                <h2 class="section-title">Thống kê bài viết</h2>
            </div>
            <div style="text-align: center; color: var(--c-text-tertiary); padding: 2rem 0;">
                <i class="ph ph-chart-bar" style="font-size: 3rem; margin-bottom: 1rem; display: block; opacity: 0.3;"></i>
                <h4 style="margin: 0 0 0.5rem 0; color: var(--c-text-secondary);">Thống kê sẽ có sớm</h4>
                <p style="margin: 0; font-size: 0.875rem;">Views, engagement, social shares, search rankings</p>
                <div style="margin-top: 1rem; display: flex; justify-content: center; gap: 2rem; font-size: 0.75rem;">
                    <div>
                        <div style="color: var(--c-text-secondary); font-weight: 600; font-size: 1.5rem;">0</div>
                        <div>Lượt xem</div>
                    </div>
                    <div>
                        <div style="color: var(--c-text-secondary); font-weight: 600; font-size: 1.5rem;">0</div>
                        <div>Chia sẻ</div>
                    </div>
                    <div>
                        <div style="color: var(--c-text-secondary); font-weight: 600; font-size: 1.5rem;">0</div>
                        <div>Bình luận</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Structured Data Section -->
<div class="content-section">
    <div class="section-header">
        <i class="ph ph-code section-icon" style="color: var(--c-purple-500);"></i>
        <h2 class="section-title">Structured Data Preview</h2>
        <button onclick="toggleStructuredData()" class="btn-outline" style="margin-left: auto; padding: 0.5rem 1rem; font-size: 0.75rem;">
            <i class="ph ph-eye" id="toggleIcon"></i>
            <span id="toggleText">Hiện</span>
        </button>
    </div>
    <div id="structuredDataContent" style="display: none;">
        <pre style="background-color: var(--c-gray-800); padding: 1.5rem; border-radius: 8px; overflow-x: auto; font-size: 0.75rem; line-height: 1.4; margin: 0;"><code>{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "{{ addslashes($article->title) }}",
  "description": "{{ addslashes(Str::limit(strip_tags($article->content), 150)) }}",
  "url": "{{ url('/' . $article->slug) }}",
  @if($article->featured_image)
  "image": "{{ $article->featured_image }}",
  @endif
  "author": {
    "@type": "Person",
    "name": "{{ addslashes($article->author->username ?: $article->author->display_name) }}",
    "email": "{{ $article->author->email }}"
  },
  "publisher": {
    "@type": "Organization",
    "name": "{{ config('app.name') }}"
  },
  "datePublished": "{{ ($article->published_at ?: $article->created_at)->toISOString() }}",
  "dateModified": "{{ $article->updated_at->toISOString() }}",
  "wordCount": {{ str_word_count(strip_tags($article->content)) }},
  "timeRequired": "PT{{ $article->reading_time }}M",
  "articleBody": {{ json_encode(strip_tags($article->content)) }},
  "keywords": "{{ implode(', ', array_unique(array_slice(explode(' ', strtolower(preg_replace('/[^a-zA-Z0-9\s]/', '', strip_tags($article->content)))), 0, 10))) }}",
  "inLanguage": "vi-VN",
  "isAccessibleForFree": true,
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ url('/' . $article->slug) }}"
  }
}</code></pre>
        <div style="margin-top: 1rem; padding: 1rem; background-color: var(--c-gray-600); border-radius: 6px; font-size: 0.875rem;">
            <strong style="color: var(--c-text-primary);">Structured Data giúp:</strong>
            <ul style="margin: 0.5rem 0; padding-left: 1.5rem; color: var(--c-text-secondary);">
                <li>Google hiểu nội dung bài viết tốt hơn</li>
                <li>Cải thiện SEO và ranking</li>
                <li>Hiển thị rich snippets trong search results</li>
                <li>Tăng click-through rate từ search engines</li>
            </ul>
        </div>
    </div>
</div>

<!-- Raw Content Preview -->
<div class="content-section">
    <div class="section-header">
        <i class="ph ph-file-html section-icon" style="color: var(--c-red-500);"></i>
        <h2 class="section-title">Raw Content</h2>
        <button onclick="toggleRawContent()" class="btn-outline" style="margin-left: auto; padding: 0.5rem 1rem; font-size: 0.75rem;">
            <i class="ph ph-eye" id="rawToggleIcon"></i>
            <span id="rawToggleText">Hiện</span>
        </button>
    </div>
    <div id="rawContentArea" style="display: none;">
        <pre style="background-color: var(--c-gray-800); padding: 1.5rem; border-radius: 8px; overflow-x: auto; font-size: 0.75rem; line-height: 1.4; margin: 0; white-space: pre-wrap; word-wrap: break-word;"><code>{{ $article->content }}</code></pre>
        <div style="margin-top: 1rem; padding: 1rem; background-color: var(--c-gray-600); border-radius: 6px; font-size: 0.875rem;">
            <strong style="color: var(--c-text-primary);">Raw Content hiển thị:</strong>
            <ul style="margin: 0.5rem 0; padding-left: 1.5rem; color: var(--c-text-secondary);">
                <li>Nội dung gốc không được format</li>
                <li>HTML tags nếu có</li>
                <li>Markdown syntax nếu sử dụng</li>
                <li>Debugging content issues</li>
            </ul>
        </div>
    </div>
</div>

<!-- Back to Top Button -->
<button id="backToTop" style="position: fixed; bottom: 2rem; right: 2rem; width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, var(--c-green-500), var(--c-blue-500)); color: var(--c-white); border: none; cursor: pointer; font-size: 1.25rem; display: none; z-index: 1000; transition: 0.3s ease; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);" onclick="scrollToTop()" onmouseenter="this.style.transform='translateY(-2px) scale(1.05)'" onmouseleave="this.style.transform='translateY(0) scale(1)'">
    <i class="ph ph-arrow-up"></i>
</button>

<!-- Keyboard Shortcuts Help -->
<button id="helpButton" style="position: fixed; bottom: 5rem; right: 2rem; width: 40px; height: 40px; border-radius: 50%; background-color: var(--c-gray-600); color: var(--c-text-secondary); border: 1px solid var(--c-gray-500); cursor: pointer; font-size: 1rem; z-index: 999; transition: 0.3s ease; display: flex; align-items: center; justify-content: center;" title="Phím tắt: E(Edit), B(Back), P(Publish), F(Featured), Ctrl+Shift+C(Copy URL)" onmouseenter="this.style.backgroundColor='var(--c-gray-500)'; this.style.color='var(--c-text-primary)'; this.style.transform='scale(1.1)';" onmouseleave="this.style.backgroundColor='var(--c-gray-600)'; this.style.color='var(--c-text-secondary)'; this.style.transform='scale(1)';">
    <i class="ph ph-question"></i>
</button>
@endsection
