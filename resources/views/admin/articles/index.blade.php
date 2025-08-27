@extends('layouts.admin')

@section('title', 'Quản lý bài viết')
@section('page-description', 'Tạo, chỉnh sửa và quản lý tất cả bài viết')

@push('styles')
    <style>
        /* Article specific styles */
        .article-thumbnail {
            width: 64px;
            height: 48px;
            border-radius: 4px;
            object-fit: cover;
            background-color: var(--c-gray-600);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--c-text-tertiary);
            font-size: 1.5rem;
        }

        .article-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .action-btn {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            border: 1px solid var(--c-gray-500);
            background: transparent;
            color: var(--c-text-secondary);
            cursor: pointer;
            transition: 0.25s ease;
            text-decoration: none;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .action-btn:hover {
            background-color: var(--c-gray-600);
            color: var(--c-text-primary);
            border-color: var(--c-gray-400);
        }

        .action-btn.btn-danger {
            border-color: var(--c-red-500);
            color: var(--c-red-400);
        }

        .action-btn.btn-danger:hover {
            background-color: var(--c-red-500);
            color: var(--c-white);
        }

        .filter-bar {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background-color: var(--c-gray-700);
            border-radius: 6px;
            border: 1px solid var(--c-gray-600);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .form-group label {
            font-size: 0.75rem;
            color: var(--c-text-tertiary);
            margin: 0;
        }

        .form-input {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--c-gray-500);
            border-radius: 4px;
            background-color: var(--c-gray-800);
            color: var(--c-text-secondary);
            font-size: 0.875rem;
            min-width: 180px;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--c-blue-500);
            box-shadow: 0 0 0 2px rgba(79, 172, 254, 0.2);
        }

        .pagination-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.5rem;
            padding: 1rem;
            background-color: var(--c-gray-700);
            border-radius: 6px;
            border: 1px solid var(--c-gray-600);
        }

        .pagination-info {
            font-size: 0.875rem;
            color: var(--c-text-tertiary);
        }

        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 0.25rem;
        }

        .pagination li {
            display: flex;
        }

        .pagination a,
        .pagination span {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--c-gray-500);
            color: var(--c-text-secondary);
            text-decoration: none;
            border-radius: 4px;
            transition: 0.25s ease;
        }

        .pagination a:hover {
            background-color: var(--c-gray-600);
            color: var(--c-text-primary);
        }

        .pagination .active span {
            background-color: var(--c-blue-500);
            color: var(--c-white);
            border-color: var(--c-blue-500);
        }

        .pagination .disabled span {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .featured-star {
            color: var(--c-orange-500);
            cursor: pointer;
            transition: 0.25s ease;
        }

        .featured-star:hover {
            color: var(--c-orange-400);
            transform: scale(1.1);
        }

        .no-data {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--c-text-tertiary);
        }

        .no-data i {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
        }

        .bulk-actions {
            display: none;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1rem;
            background-color: var(--c-blue-600);
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        .bulk-actions.show {
            display: flex;
        }

        .bulk-actions-text {
            color: var(--c-white);
            font-size: 0.875rem;
        }

        .bulk-btn {
            padding: 0.375rem 0.75rem;
            border: 1px solid var(--c-white);
            background: transparent;
            color: var(--c-white);
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.75rem;
            transition: 0.25s ease;
        }

        .bulk-btn:hover {
            background-color: var(--c-white);
            color: var(--c-blue-600);
        }
    </style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h1>Quản lý bài viết</h1>
                <p>Tạo, chỉnh sửa và quản lý tất cả bài viết trên website</p>
            </div>
            @if(auth()->user()->hasPermission('articles.create'))
                <a href="{{ route('admin.articles.create') }}" class="btn-primary">
                    <i class="ph ph-plus" style="margin-right: 0.5rem;"></i>
                    Tạo bài viết mới
                </a>
            @endif
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>{{ $articles->total() }}</h3>
            <p>
                <i class="ph ph-article" style="margin-right: 0.25rem; color: var(--c-text-tertiary);"></i>
                Tổng bài viết
            </p>
        </div>
        <div class="stat-card">
            <h3>{{ $articles->where('status', 'published')->count() }}</h3>
            <p>
                <i class="ph ph-check-circle" style="margin-right: 0.25rem; color: var(--c-green-500);"></i>
                Đã xuất bản
            </p>
        </div>
        <div class="stat-card">
            <h3>{{ $articles->where('status', 'draft')->count() }}</h3>
            <p>
                <i class="ph ph-file-dashed" style="margin-right: 0.25rem; color: var(--c-orange-500);"></i>
                Bản nháp
            </p>
        </div>
        <div class="stat-card">
            <h3>{{ $articles->where('is_featured', true)->count() }}</h3>
            <p>
                <i class="ph ph-star" style="margin-right: 0.25rem; color: var(--c-orange-500);"></i>
                Nổi bật
            </p>
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

    <!-- Bulk Actions Bar (Hidden by default) -->
    <div id="bulkActions" class="bulk-actions">
        <span class="bulk-actions-text">
            <span id="selectedCount">0</span> bài viết được chọn
        </span>
        @if(auth()->user()->hasPermission('articles.publish'))
            <button type="button" class="bulk-btn" onclick="bulkAction('publish')">
                <i class="ph ph-upload-simple"></i> Xuất bản
            </button>
        @endif
        <button type="button" class="bulk-btn" onclick="bulkAction('archive')">
            <i class="ph ph-archive"></i> Lưu trữ
        </button>
        @if(auth()->user()->hasPermission('articles.delete'))
            <button type="button" class="bulk-btn" onclick="bulkAction('delete')"
                style="border-color: var(--c-red-400); color: var(--c-red-400);">
                <i class="ph ph-trash"></i> Xóa
            </button>
        @endif
        <button type="button" class="bulk-btn" onclick="clearSelection()">
            <i class="ph ph-x"></i> Hủy
        </button>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <form method="GET" style="display: flex; gap: 1rem; align-items: end; flex-wrap: wrap; width: 100%;">
            <div class="form-group">
                <label for="search">Tìm kiếm</label>
                <input type="text" name="search" id="search" class="form-input" placeholder="Tìm theo tiêu đề, nội dung..."
                    value="{{ request('search') }}">
            </div>

            <div class="form-group">
                <label for="status">Trạng thái</label>
                <select name="status" id="status" class="form-input">
                    <option value="">Tất cả</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Bản nháp</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Lưu trữ</option>
                </select>
            </div>

            @if(auth()->user()->hasPermission('articles.manage_all') && isset($authors) && $authors->count() > 0)
                <div class="form-group">
                    <label for="author">Tác giả</label>
                    <select name="author" id="author" class="form-input">
                        <option value="">Tất cả tác giả</option>
                        @foreach($authors as $author)
                            <option value="{{ $author['id'] }}" {{ request('author') == $author['id'] ? 'selected' : '' }}>
                                {{ $author['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="form-group">
                <label for="featured">Nổi bật</label>
                <select name="featured" id="featured" class="form-input">
                    <option value="">Tất cả</option>
                    <option value="1" {{ request('featured') === '1' ? 'selected' : '' }}>Nổi bật</option>
                    <option value="0" {{ request('featured') === '0' ? 'selected' : '' }}>Không nổi bật</option>
                </select>
            </div>

            <div class="form-group">
                <label for="sort">Sắp xếp</label>
                <select name="sort" id="sort" class="form-input">
                    <option value="created_at" {{ request('sort', 'created_at') === 'created_at' ? 'selected' : '' }}>Ngày tạo
                    </option>
                    <option value="published_at" {{ request('sort') === 'published_at' ? 'selected' : '' }}>Ngày xuất bản
                    </option>
                    <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>Tiêu đề</option>
                    <option value="status" {{ request('sort') === 'status' ? 'selected' : '' }}>Trạng thái</option>
                </select>
            </div>

            <div class="form-group">
                <label for="order">Thứ tự</label>
                <select name="order" id="order" class="form-input">
                    <option value="desc" {{ request('order', 'desc') === 'desc' ? 'selected' : '' }}>Giảm dần</option>
                    <option value="asc" {{ request('order') === 'asc' ? 'selected' : '' }}>Tăng dần</option>
                </select>
            </div>

            <button type="submit" class="flat-button">
                <i class="ph ph-magnifying-glass"></i>
                Lọc
            </button>

            @if(request()->hasAny(['search', 'status', 'author', 'featured', 'sort', 'order']))
                <a href="{{ route('admin.articles.index') }}" class="flat-button"
                    style="border-color: var(--c-red-500); color: var(--c-red-400);">
                    <i class="ph ph-x"></i>
                    Xóa bộ lọc
                </a>
            @endif
        </form>
    </div>

    <!-- Articles Table -->
    <div class="content-section">
        @if($articles->count() > 0)
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 40px;">
                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                            </th>
                            <th style="width: 60px;">Hình</th>
                            <th style="width: 35%;">Tiêu đề</th>
                            <th style="width: 12%;">Trạng thái</th>
                            <th style="width: 15%;">Tác giả</th>
                            <th style="width: 12%;">Ngày tạo</th>
                            <th style="width: 12%;">Xuất bản</th>
                            <th style="width: 120px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($articles as $article)
                            <tr>
                                <td>
                                    <input type="checkbox" name="article_ids[]" value="{{ $article->id }}" class="article-checkbox"
                                        onchange="updateBulkActions()">
                                </td>
                                <td>
                                    @if($article->featured_image)
                                        <img src="{{ $article->featured_image }}" alt="{{ $article->title }}" class="article-thumbnail">
                                    @else
                                        <div class="article-thumbnail">
                                            <i class="ph ph-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        @if($article->is_featured)
                                            <i class="ph ph-star-fill featured-star" title="Bài viết nổi bật"
                                                onclick="toggleFeatured({{ $article->id }})"></i>
                                        @else
                                            <i class="ph ph-star featured-star" title="Đánh dấu nổi bật"
                                                onclick="toggleFeatured({{ $article->id }})" style="opacity: 0.3;"></i>
                                        @endif
                                        <div>
                                            <a href="{{ route('admin.articles.show', $article) }}"
                                                style="color: var(--c-text-primary); text-decoration: none; font-weight: 500;">
                                                {{ $article->title }}
                                            </a>
                                            <div style="font-size: 0.75rem; color: var(--c-text-tertiary); margin-top: 0.25rem;">
                                                {{ Str::limit(strip_tags($article->content), 80) }}
                                            </div>
                                            <div style="font-size: 0.75rem; color: var(--c-text-tertiary); margin-top: 0.25rem;">
                                                <i class="ph ph-clock" style="margin-right: 0.25rem;"></i>
                                                {{ $article->reading_time }} phút đọc
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($article->status === 'published')
                                        <span class="badge badge-success">Đã xuất bản</span>
                                    @elseif($article->status === 'draft')
                                        <span class="badge badge-secondary">Bản nháp</span>
                                    @else
                                        <span class="badge badge-warning">Lưu trữ</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <div
                                            style="width: 24px; height: 24px; border-radius: 50%; background: linear-gradient(135deg, var(--c-green-500), var(--c-blue-500)); display: flex; align-items: center; justify-content: center; font-size: 0.6rem; font-weight: 600; color: var(--c-gray-900);">
                                            {{ strtoupper(substr($article->author->username ?: $article->author->email, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div style="font-size: 0.875rem;">
                                                {{ $article->author->username ?: explode('@', $article->author->email)[0] }}</div>
                                            <div style="font-size: 0.75rem; color: var(--c-text-tertiary);">
                                                {{ $article->author->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size: 0.875rem;">{{ $article->created_at->format('d/m/Y') }}</div>
                                    <div style="font-size: 0.75rem; color: var(--c-text-tertiary);">
                                        {{ $article->created_at->format('H:i') }}</div>
                                </td>
                                <td>
                                    @if($article->published_at)
                                        <div style="font-size: 0.875rem;">{{ $article->published_at->format('d/m/Y') }}</div>
                                        <div style="font-size: 0.75rem; color: var(--c-text-tertiary);">
                                            {{ $article->published_at->format('H:i') }}</div>
                                    @else
                                        <span style="color: var(--c-text-tertiary); font-size: 0.75rem;">Chưa xuất bản</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="article-actions">
                                        <a href="{{ route('admin.articles.show', $article) }}" class="action-btn"
                                            title="Xem chi tiết">
                                            <i class="ph ph-eye"></i>
                                        </a>

                                        @if($article->canEdit())
                                            <a href="{{ route('admin.articles.edit', $article) }}" class="action-btn" title="Chỉnh sửa">
                                                <i class="ph ph-pencil-simple"></i>
                                            </a>

                                            @if($article->status === 'draft' && auth()->user()->hasPermission('articles.publish'))
                                                <form method="POST" action="{{ route('admin.articles.publish', $article) }}"
                                                    style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="action-btn" title="Xuất bản"
                                                        onclick="return confirm('Bạn có chắc muốn xuất bản bài viết này?')">
                                                        <i class="ph ph-upload-simple"></i>
                                                    </button>
                                                </form>
                                            @elseif($article->status === 'published' && auth()->user()->hasPermission('articles.publish'))
                                                <form method="POST" action="{{ route('admin.articles.unpublish', $article) }}"
                                                    style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="action-btn" title="Hủy xuất bản"
                                                        onclick="return confirm('Bạn có chắc muốn hủy xuất bản bài viết này?')">
                                                        <i class="ph ph-download-simple"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if(auth()->user()->hasPermission('articles.delete'))
                                                <form method="POST" action="{{ route('admin.articles.destroy', $article) }}"
                                                    style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn btn-danger" title="Xóa bài viết"
                                                        onclick="return confirm('Bạn có chắc muốn xóa bài viết này? Hành động này không thể hoàn tác!')">
                                                        <i class="ph ph-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($articles->hasPages())
                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        Hiển thị {{ $articles->firstItem() }}-{{ $articles->lastItem() }}
                        trong tổng số {{ $articles->total() }} bài viết
                    </div>

                    <nav>
                        <ul class="pagination">
                            {{-- Previous Page Link --}}
                            @if ($articles->onFirstPage())
                                <li class="disabled"><span>&laquo; Trước</span></li>
                            @else
                                <li><a href="{{ $articles->previousPageUrl() }}">&laquo; Trước</a></li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($articles->getUrlRange(1, $articles->lastPage()) as $page => $url)
                                @if ($page == $articles->currentPage())
                                    <li class="active"><span>{{ $page }}</span></li>
                                @else
                                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($articles->hasMorePages())
                                <li><a href="{{ $articles->nextPageUrl() }}">Tiếp &raquo;</a></li>
                            @else
                                <li class="disabled"><span>Tiếp &raquo;</span></li>
                            @endif
                        </ul>
                    </nav>
                </div>
            @endif
        @else
            <div class="no-data">
                <i class="ph ph-article"></i>
                <h3>Không có bài viết nào</h3>
                <p>
                    @if(request()->hasAny(['search', 'status', 'author', 'featured']))
                        Không tìm thấy bài viết nào phù hợp với bộ lọc hiện tại.
                        <br>
                        <a href="{{ route('admin.articles.index') }}" class="flat-button" style="margin-top: 1rem;">
                            Xóa bộ lọc
                        </a>
                    @else
                        Chưa có bài viết nào được tạo.
                        @if(auth()->user()->hasPermission('articles.create'))
                            <br>
                            <a href="{{ route('admin.articles.create') }}" class="btn-primary" style="margin-top: 1rem;">
                                Tạo bài viết đầu tiên
                            </a>
                        @endif
                    @endif
                </p>
            </div>
        @endif
    </div>

    <!-- CSRF Token for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('scripts')
    <script>
        // CSRF token setup for AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Select All functionality
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.article-checkbox');

            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });

            updateBulkActions();
        }

        // Update bulk actions visibility
        function updateBulkActions() {
            const checkboxes = document.querySelectorAll('.article-checkbox:checked');
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');

            if (checkboxes.length > 0) {
                bulkActions.classList.add('show');
                selectedCount.textContent = checkboxes.length;
            } else {
                bulkActions.classList.remove('show');
            }

            // Update select all checkbox state
            const selectAll = document.getElementById('selectAll');
            const allCheckboxes = document.querySelectorAll('.article-checkbox');
            selectAll.indeterminate = checkboxes.length > 0 && checkboxes.length < allCheckboxes.length;
            selectAll.checked = checkboxes.length === allCheckboxes.length && allCheckboxes.length > 0;
        }

        // Clear selection
        function clearSelection() {
            document.querySelectorAll('.article-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            document.getElementById('selectAll').checked = false;
            updateBulkActions();
        }

        // Bulk actions
        function bulkAction(action) {
            const checkboxes = document.querySelectorAll('.article-checkbox:checked');
            const articleIds = Array.from(checkboxes).map(cb => cb.value);

            if (articleIds.length === 0) {
                alert('Vui lòng chọn ít nhất một bài viết');
                return;
            }

            let confirmMessage = '';
            switch (action) {
                case 'publish':
                    confirmMessage = `Bạn có chắc muốn xuất bản ${articleIds.length} bài viết được chọn?`;
                    break;
                case 'archive':
                    confirmMessage = `Bạn có chắc muốn lưu trữ ${articleIds.length} bài viết được chọn?`;
                    break;
                case 'delete':
                    confirmMessage = `Bạn có chắc muốn xóa ${articleIds.length} bài viết được chọn? Hành động này không thể hoàn tác!`;
                    break;
                default:
                    return;
            }

            if (!confirm(confirmMessage)) {
                return;
            }

            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/api/articles/bulk/${action}`;

            // CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);

            // Article IDs
            articleIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'article_ids[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }

        // Toggle featured status
        function toggleFeatured(articleId) {
            if (!confirm('Bạn có muốn thay đổi trạng thái nổi bật của bài viết này?')) {
                return;
            }

            fetch(`/admin/articles/${articleId}/toggle-featured`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Simple reload to update the UI
                    } else {
                        alert(data.message || 'Có lỗi xảy ra');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi thực hiện thao tác');
                });
        }

        // Auto-submit form when filter changes (optional)
        document.addEventListener('DOMContentLoaded', function () {
            const filterSelects = document.querySelectorAll('#status, #author, #featured, #sort, #order');

            filterSelects.forEach(select => {
                select.addEventListener('change', function () {
                    // Optionally auto-submit the form
                    // this.form.submit();
                });
            });

            // Search input with debounce
            const searchInput = document.getElementById('search');
            let searchTimeout;

            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    // Optionally auto-submit search
                    // this.form.submit();
                }, 500);
            });

            // Initialize bulk actions state
            updateBulkActions();
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function (e) {
            // Ctrl/Cmd + A to select all (when focused on table)
            if ((e.ctrlKey || e.metaKey) && e.key === 'a') {
                const table = document.querySelector('.table');
                if (table && table.contains(document.activeElement)) {
                    e.preventDefault();
                    document.getElementById('selectAll').checked = true;
                    toggleSelectAll();
                }
            }

            // Escape to clear selection
            if (e.key === 'Escape') {
                clearSelection();
            }
        });
    </script>
@endpush
