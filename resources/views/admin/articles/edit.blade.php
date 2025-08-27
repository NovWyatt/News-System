@extends('layouts.admin')

@section('title', 'Chỉnh sửa bài viết')
@section('page-description', 'Cập nhật thông tin và nội dung bài viết')

@push('styles')
<style>
    /* Form styling */
    .form-row {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        flex: 1;
    }

    .form-group.full-width {
        flex: 1 1 100%;
    }

    .form-label {
        font-weight: 600;
        color: var(--c-text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label .required {
        color: var(--c-red-500);
        font-size: 0.875rem;
    }

    .form-input,
    .form-textarea,
    .form-select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--c-gray-600);
        border-radius: 6px;
        background-color: var(--c-gray-700);
        color: var(--c-text-primary);
        font-size: 0.875rem;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .form-input:focus,
    .form-textarea:focus,
    .form-select:focus {
        outline: none;
        border-color: var(--c-green-500);
        box-shadow: 0 0 0 2px rgba(69, 255, 188, 0.2);
    }

    .form-textarea {
        resize: vertical;
        min-height: 200px;
        font-family: 'Monaco', 'Consolas', 'Courier New', monospace;
        line-height: 1.6;
    }

    .form-textarea.large {
        min-height: 400px;
    }

    .form-input::placeholder,
    .form-textarea::placeholder {
        color: var(--c-text-tertiary);
        opacity: 0.7;
    }

    .form-help {
        font-size: 0.75rem;
        color: var(--c-text-tertiary);
        margin-top: 0.25rem;
    }

    .form-error {
        font-size: 0.75rem;
        color: var(--c-red-400);
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* Checkbox styling */
    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-top: 0.5rem;
    }

    .checkbox-input {
        width: 1.25rem;
        height: 1.25rem;
        border: 2px solid var(--c-gray-500);
        border-radius: 4px;
        background-color: var(--c-gray-700);
        cursor: pointer;
        transition: all 0.25s ease;
        position: relative;
    }

    .checkbox-input:checked {
        background-color: var(--c-green-500);
        border-color: var(--c-green-500);
    }

    .checkbox-input:checked::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: var(--c-gray-900);
        font-weight: bold;
        font-size: 0.875rem;
    }

    .checkbox-label {
        color: var(--c-text-secondary);
        cursor: pointer;
        user-select: none;
        font-size: 0.875rem;
    }

    /* Card styling */
    .form-card {
        background-color: var(--c-gray-700);
        border-radius: 8px;
        padding: 2rem;
        border: 1px solid var(--c-gray-600);
        margin-bottom: 2rem;
    }

    .form-card-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--c-gray-600);
    }

    .form-card-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--c-text-primary);
        margin: 0;
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

    /* Status indicators */
    .status-indicator {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        color: var(--c-text-tertiary);
        padding: 0.5rem 0.75rem;
        background-color: var(--c-gray-600);
        border-radius: 4px;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .status-dot.draft {
        background-color: var(--c-orange-500);
    }

    .status-dot.published {
        background-color: var(--c-green-500);
    }

    .status-dot.archived {
        background-color: var(--c-gray-400);
    }

    /* Article info bar */
    .article-info {
        background-color: var(--c-gray-600);
        border-radius: 6px;
        padding: 1rem;
        margin-bottom: 2rem;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        font-size: 0.75rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .info-item-value {
        font-size: 1rem;
        font-weight: 600;
        color: var(--c-text-primary);
        margin-bottom: 0.25rem;
    }

    .info-item-label {
        color: var(--c-text-tertiary);
    }

    /* Button variations */
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
    }

    .btn-outline:hover {
        background-color: var(--c-gray-600);
        color: var(--c-text-primary);
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
        transform: translateY(-1px);
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
        transform: translateY(-1px);
    }

    /* Slug preview */
    .slug-preview {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.5rem;
        padding: 0.5rem 0.75rem;
        background-color: var(--c-gray-600);
        border-radius: 4px;
        font-family: 'Monaco', 'Consolas', 'Courier New', monospace;
        font-size: 0.75rem;
    }

    .slug-preview-url {
        color: var(--c-text-tertiary);
    }

    .slug-preview-slug {
        color: var(--c-green-400);
        font-weight: 600;
    }

    /* Reading time estimate */
    .reading-time {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        color: var(--c-text-tertiary);
        margin-top: 0.5rem;
    }

    /* Image upload area */
    .image-upload {
        border: 2px dashed var(--c-gray-500);
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        transition: 0.25s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .image-upload:hover {
        border-color: var(--c-green-500);
        background-color: rgba(69, 255, 188, 0.05);
    }

    .image-upload.has-image {
        border-style: solid;
        border-color: var(--c-green-500);
        padding: 1rem;
    }

    .image-upload-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .image-upload-icon {
        font-size: 3rem;
        color: var(--c-text-tertiary);
    }

    .image-upload-text {
        color: var(--c-text-secondary);
    }

    .image-preview {
        max-width: 100%;
        max-height: 200px;
        border-radius: 6px;
        object-fit: cover;
    }

    /* Change tracking */
    .change-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.6rem;
        color: var(--c-orange-400);
        opacity: 0;
        transition: opacity 0.25s ease;
    }

    .change-indicator.show {
        opacity: 1;
    }

    /* Version info */
    .version-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.75rem;
        color: var(--c-text-tertiary);
        padding: 0.75rem;
        background-color: var(--c-gray-600);
        border-radius: 4px;
        margin-bottom: 1rem;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
            gap: 1rem;
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

        .form-card {
            padding: 1.5rem;
        }

        .article-info {
            grid-template-columns: 1fr 1fr;
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
                Quay lại
            </a>
            <div class="status-indicator">
                <span class="status-dot {{ $article->status }}"></span>
                @if($article->status === 'published')
                Đã xuất bản
                @elseif($article->status === 'draft')
                Bản nháp
                @else
                Lưu trữ
                @endif
                <span id="changeIndicator" class="change-indicator">
                    <i class="ph ph-pencil-simple"></i>
                    Đã chỉnh sửa
                </span>
            </div>
            <a href="{{ route('admin.articles.show', $article) }}" class="btn-outline">
                <i class="ph ph-eye"></i>
                Xem chi tiết
            </a>
        </div>
        <div class="actions-right">
            <button type="button" class="btn-outline" onclick="saveDraft()">
                <i class="ph ph-floppy-disk"></i>
                Lưu thay đổi
            </button>

            @if($article->status === 'draft' && auth()->user()->hasPermission('articles.publish'))
            <button type="button" class="btn-primary" onclick="publishArticle()">
                <i class="ph ph-upload-simple"></i>
                Xuất bản
            </button>
            @elseif($article->status === 'published' && auth()->user()->hasPermission('articles.publish'))
            <button type="button" class="btn-warning" onclick="unpublishArticle()">
                <i class="ph ph-download-simple"></i>
                Hủy xuất bản
            </button>
            @endif

            @if(auth()->user()->hasPermission('articles.delete'))
            <button type="button" class="btn-danger" onclick="deleteArticle()">
                <i class="ph ph-trash"></i>
                Xóa bài viết
            </button>
            @endif
        </div>
    </div>
</div>

<!-- Article Info -->
<div class="version-info">
    <i class="ph ph-info"></i>
    <span>Tạo bởi <strong>{{ $article->author->username ?: $article->author->display_name }}</strong> vào {{ $article->created_at->format('d/m/Y H:i') }}</span>
    <span>•</span>
    <span>Cập nhật lần cuối: {{ $article->updated_at->format('d/m/Y H:i') }}</span>
    @if($article->published_at)
    <span>•</span>
    <span>Xuất bản: {{ $article->published_at->format('d/m/Y H:i') }}</span>
    @endif
</div>

<!-- Article Statistics -->
<div class="article-info">
    <div class="info-item">
        <div class="info-item-value">{{ $article->reading_time }}</div>
        <div class="info-item-label">Phút đọc</div>
    </div>
    <div class="info-item">
        <div class="info-item-value" id="currentWordCount">{{ str_word_count(strip_tags($article->content)) }}</div>
        <div class="info-item-label">Số từ</div>
    </div>
    <div class="info-item">
        <div class="info-item-value" id="currentCharCount">{{ strlen($article->content) }}</div>
        <div class="info-item-label">Ký tự</div>
    </div>
    <div class="info-item">
        <div class="info-item-value">
            @if($article->is_featured)
            <i class="ph ph-star-fill" style="color: var(--c-orange-500);"></i>
            @else
            <i class="ph ph-star" style="color: var(--c-text-tertiary);"></i>
            @endif
        </div>
        <div class="info-item-label">Nổi bật</div>
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

<!-- Main Form -->
<form id="articleForm" action="{{ route('admin.articles.update', $article) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Basic Information -->
    <div class="form-card">
        <div class="form-card-header">
            <i class="ph ph-article" style="color: var(--c-green-500); font-size: 1.5rem;"></i>
            <h2 class="form-card-title">Thông tin cơ bản</h2>
        </div>

        <div class="form-row">
            <div class="form-group full-width">
                <label class="form-label" for="title">
                    <i class="ph ph-text-aa"></i>
                    Tiêu đề bài viết
                    <span class="required">*</span>
                </label>
                <input type="text" name="title" id="title" class="form-input" placeholder="Nhập tiêu đề bài viết..." value="{{ old('title', $article->title) }}" required onkeyup="updateSlug(); updateReadingTime(); trackChanges();">
                @error('title')
                <div class="form-error">
                    <i class="ph ph-warning-circle"></i>
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="slug">
                    <i class="ph ph-link"></i>
                    Slug (URL thân thiện)
                </label>
                <input type="text" name="slug" id="slug" class="form-input" placeholder="duoc-tao-tu-dong-tu-tieu-de" value="{{ old('slug', $article->slug) }}" onkeyup="updateSlugPreview(); trackChanges();">
                <div class="form-help">
                    Để trống để tự động tạo từ tiêu đề. Chỉ sử dụng chữ cái, số và dấu gạch ngang.
                </div>
                <div id="slugPreview" class="slug-preview">
                    <span class="slug-preview-url">{{ url('/') }}/</span>
                    <span class="slug-preview-slug" id="slugPreviewText">{{ $article->slug }}</span>
                </div>
                @error('slug')
                <div class="form-error">
                    <i class="ph ph-warning-circle"></i>
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="status">
                    <i class="ph ph-traffic-sign"></i>
                    Trạng thái
                </label>
                <select name="status" id="status" class="form-select" onchange="togglePublishDate(); trackChanges();">
                    <option value="draft" {{ old('status', $article->status) === 'draft' ? 'selected' : '' }}>Bản nháp</option>
                    @if(auth()->user()->hasPermission('articles.publish'))
                    <option value="published" {{ old('status', $article->status) === 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                    @endif
                    <option value="archived" {{ old('status', $article->status) === 'archived' ? 'selected' : '' }}>Lưu trữ</option>
                </select>
                @error('status')
                <div class="form-error">
                    <i class="ph ph-warning-circle"></i>
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="form-card">
        <div class="form-card-header">
            <i class="ph ph-text-align-left" style="color: var(--c-blue-500); font-size: 1.5rem;"></i>
            <h2 class="form-card-title">Nội dung bài viết</h2>
            <div id="readingTime" class="reading-time" style="margin-left: auto;">
                <i class="ph ph-clock"></i>
                <span id="readingTimeText">{{ $article->reading_time }} phút đọc</span>
            </div>
        </div>

        <div class="form-group full-width">
            <label class="form-label" for="content">
                <i class="ph ph-text-align-justify"></i>
                Nội dung
                <span class="required">*</span>
            </label>
            <textarea name="content" id="content" class="form-textarea large" placeholder="Nhập nội dung bài viết... Hỗ trợ Markdown và HTML" required onkeyup="updateReadingTime(); trackChanges();">{{ old('content', $article->content) }}</textarea>
            <div class="form-help">
                Bạn có thể sử dụng Markdown hoặc HTML để định dạng văn bản. Ví dụ: **in đậm**, *in nghiêng*, [liên kết](URL)
            </div>
            @error('content')
            <div class="form-error">
                <i class="ph ph-warning-circle"></i>
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>

    <!-- Media & Settings -->
    <div class="form-row">
        <!-- Featured Image -->
        <div class="form-card" style="margin-bottom: 0;">
            <div class="form-card-header">
                <i class="ph ph-image" style="color: var(--c-purple-500); font-size: 1.5rem;"></i>
                <h2 class="form-card-title">Hình ảnh đại diện</h2>
            </div>

            <div class="form-group">
                <label class="form-label" for="featured_image">
                    <i class="ph ph-image-square"></i>
                    URL hình ảnh
                </label>
                <input type="url" name="featured_image" id="featured_image" class="form-input" placeholder="https://example.com/image.jpg" value="{{ old('featured_image', $article->featured_image) }}" onchange="previewImage(this.value); trackChanges();">
                <div class="form-help">
                    Nhập URL của hình ảnh hoặc upload hình ảnh và copy URL
                </div>
                @error('featured_image')
                <div class="form-error">
                    <i class="ph ph-warning-circle"></i>
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div id="imageUploadArea" class="image-upload {{ $article->featured_image ? 'has-image' : '' }}" onclick="document.getElementById('imageFile').click();">
                <input type="file" id="imageFile" accept="image/*" style="display: none;" onchange="handleImageUpload(this)">
                <div class="image-upload-content" id="uploadContent" style="{{ $article->featured_image ? 'display: none;' : '' }}">
                    <i class="ph ph-cloud-arrow-up image-upload-icon"></i>
                    <div class="image-upload-text">
                        <strong>Click để upload hình ảnh</strong><br>
                        Hoặc kéo thả hình ảnh vào đây
                    </div>
                    <div style="font-size: 0.75rem; color: var(--c-text-tertiary);">
                        Hỗ trợ: JPG, PNG, GIF (tối đa 5MB)
                    </div>
                </div>
                <img id="imagePreview" class="image-preview" src="{{ $article->featured_image }}" style="{{ $article->featured_image ? 'display: block;' : 'display: none;' }}" alt="Preview">
            </div>
        </div>

        <!-- Article Settings -->
        <div class="form-card" style="margin-bottom: 0;">
            <div class="form-card-header">
                <i class="ph ph-gear" style="color: var(--c-orange-500); font-size: 1.5rem;"></i>
                <h2 class="form-card-title">Cài đặt bài viết</h2>
            </div>

            <div class="form-group">
                <div class="checkbox-group">
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" class="checkbox-input" {{ old('is_featured', $article->is_featured) ? 'checked' : '' }} onchange="trackChanges();">
                    <label for="is_featured" class="checkbox-label">
                        <strong>Bài viết nổi bật</strong><br>
                        <small>Hiển thị ở vị trí nổi bật trên trang chủ</small>
                    </label>
                </div>
            </div>

            <div class="form-group" id="publishDateGroup" style="{{ $article->status === 'published' ? 'display: block;' : 'display: none;' }}">
                <label class="form-label" for="published_at">
                    <i class="ph ph-calendar"></i>
                    Thời gian xuất bản
                </label>
                <input type="datetime-local" name="published_at" id="published_at" class="form-input" value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}" onchange="trackChanges();">
                <div class="form-help">
                    Để trống để xuất bản ngay lập tức
                </div>
                @error('published_at')
                <div class="form-error">
                    <i class="ph ph-warning-circle"></i>
                    {{ $message }}
                </div>
                @enderror
            </div>

            <!-- Article Stats -->
            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--c-gray-600);">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; font-size: 0.75rem; color: var(--c-text-tertiary);">
                    <div>
                        <strong>Số từ:</strong>
                        <span id="wordCount">{{ str_word_count(strip_tags($article->content)) }}</span>
                    </div>
                    <div>
                        <strong>Số ký tự:</strong>
                        <span id="charCount">{{ strlen($article->content) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <!-- Form Actions -->
    <div style="display: flex; justify-content: center; gap: 1rem; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--c-gray-600);">
        <a href="{{ route('admin.articles.index') }}" class="btn-secondary">
            <i class="ph ph-x"></i>
            Hủy bỏ
        </a>
        <button type="button" class="btn-outline" onclick="saveDraft()">
            <i class="ph ph-floppy-disk"></i>
            Lưu thay đổi
        </button>

        @if($article->status === 'draft' && auth()->user()->hasPermission('articles.publish'))
        <button type="button" class="btn-primary" onclick="publishArticle()">
            <i class="ph ph-upload-simple"></i>
            Xuất bản
        </button>
        @elseif($article->status === 'published' && auth()->user()->hasPermission('articles.publish'))
        <button type="button" class="btn-warning" onclick="unpublishArticle()">
            <i class="ph ph-download-simple"></i>
            Hủy xuất bản
        </button>
        @endif

        @if(auth()->user()->hasPermission('articles.delete'))
        <button type="button" class="btn-danger" onclick="deleteArticle()">
            <i class="ph ph-trash"></i>
            Xóa bài viết
        </button>
        @endif
    </div>
</form>

<!-- Hidden Delete Form -->
<form id="deleteForm" action="{{ route('admin.articles.destroy', $article) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- CSRF Token for AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('scripts')
<script>
    // CSRF Token setup
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Store original form data for change tracking
    let originalFormData = {};
    let hasUnsavedChanges = false;

    // Auto-generate slug from title (only if current slug was auto-generated)
    function updateSlug() {
        const title = document.getElementById('title').value;
        const slugField = document.getElementById('slug');
        const originalSlug = '{{ $article->slug }}';
        const originalTitle = '{{ $article->title }}';

        // Only auto-update slug if it matches the original pattern
        if (title && slugField.value === generateSlug(originalTitle)) {
            const slug = generateSlug(title);
            slugField.value = slug;
            updateSlugPreview();
        }
    }

    // Generate URL-friendly slug
    function generateSlug(text) {
        return text
            .toLowerCase()
            .replace(/[àáạảãâầấậẩẫăằắặẳẵ]/g, 'a')
            .replace(/[èéẹẻẽêềếệểễ]/g, 'e')
            .replace(/[ìíịỉĩ]/g, 'i')
            .replace(/[òóọỏõôồốộổỗơờớợởỡ]/g, 'o')
            .replace(/[ùúụủũưừứựửữ]/g, 'u')
            .replace(/[ỳýỵỷỹ]/g, 'y')
            .replace(/đ/g, 'd')
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
    }

    // Update slug preview
    function updateSlugPreview() {
        const slug = document.getElementById('slug').value;
        const previewText = document.getElementById('slugPreviewText');

        previewText.textContent = slug || '{{ $article->slug }}';
    }

    // Calculate reading time and update stats
    function updateReadingTime() {
        const title = document.getElementById('title').value || '';
        const content = document.getElementById('content').value || '';
        const text = title + ' ' + content;

        const wordCount = text.trim() ? text.trim().split(/\s+/).length : 0;
        const charCount = text.length;
        const readingTime = Math.max(1, Math.ceil(wordCount / 200));

        document.getElementById('wordCount').textContent = wordCount.toLocaleString();
        document.getElementById('charCount').textContent = charCount.toLocaleString();
        document.getElementById('currentWordCount').textContent = wordCount.toLocaleString();
        document.getElementById('currentCharCount').textContent = charCount.toLocaleString();
        document.getElementById('readingTimeText').textContent = readingTime + ' phút đọc';
    }

    // Track form changes
    function trackChanges() {
        hasUnsavedChanges = true;
        document.getElementById('changeIndicator').classList.add('show');

        // Update action bar status
        const statusDot = document.querySelector('.status-dot');
        statusDot.className = 'status-dot ' + document.getElementById('status').value;
    }

    // Store original form data
    function storeOriginalData() {
        const form = document.getElementById('articleForm');
        const formData = new FormData(form);

        originalFormData = {};
        for (let [key, value] of formData.entries()) {
            originalFormData[key] = value;
        }

        hasUnsavedChanges = false;
    }

    // Image preview
    function previewImage(url) {
        const preview = document.getElementById('imagePreview');
        const uploadContent = document.getElementById('uploadContent');
        const uploadArea = document.getElementById('imageUploadArea');

        if (url) {
            preview.src = url;
            preview.style.display = 'block';
            uploadContent.style.display = 'none';
            uploadArea.classList.add('has-image');
        } else {
            preview.style.display = 'none';
            uploadContent.style.display = 'flex';
            uploadArea.classList.remove('has-image');
        }
    }

    // Handle image upload (placeholder - would need backend endpoint)
    function handleImageUpload(input) {
        const file = input.files[0];
        if (!file) return;

        // Validate file
        if (!file.type.startsWith('image/')) {
            alert('Vui lòng chọn file hình ảnh hợp lệ');
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            alert('File quá lớn. Vui lòng chọn file nhỏ hơn 5MB');
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            const uploadContent = document.getElementById('uploadContent');
            const uploadArea = document.getElementById('imageUploadArea');

            preview.src = e.target.result;
            preview.style.display = 'block';
            uploadContent.style.display = 'none';
            uploadArea.classList.add('has-image');

            trackChanges();

            // You would upload the file here and set the URL
            // document.getElementById('featured_image').value = uploadedUrl;
        };
        reader.readAsDataURL(file);
    }

    // Show/hide publish date based on status
    function togglePublishDate() {
        const status = document.getElementById('status').value;
        const publishDateGroup = document.getElementById('publishDateGroup');

        if (status === 'published') {
            publishDateGroup.style.display = 'block';
        } else {
            publishDateGroup.style.display = 'none';
        }
    }

    // Save as draft
    function saveDraft() {
        if (!validateForm()) return;

        document.getElementById('status').value = 'draft';
        document.getElementById('articleForm').submit();
    }

    // Publish article
    function publishArticle() {
        if (!validateForm()) return;

        if (confirm('Bạn có chắc muốn xuất bản bài viết này?')) {
            document.getElementById('status').value = 'published';
            document.getElementById('articleForm').submit();
        }
    }

    // Unpublish article
    function unpublishArticle() {
        if (confirm('Bạn có chắc muốn hủy xuất bản bài viết này? Bài viết sẽ chuyển về trạng thái bản nháp.')) {
            document.getElementById('status').value = 'draft';
            document.getElementById('articleForm').submit();
        }
    }

    // Delete article
    function deleteArticle() {
        if (confirm('Bạn có chắc muốn xóa bài viết này? Hành động này không thể hoàn tác!')) {
            if (confirm('Xác nhận lần cuối: Bài viết "{{ $article->title }}" sẽ bị xóa vĩnh viễn!')) {
                document.getElementById('deleteForm').submit();
            }
        }
    }

    // Form validation
    function validateForm() {
        const title = document.getElementById('title').value.trim();
        const content = document.getElementById('content').value.trim();

        if (!title) {
            alert('Vui lòng nhập tiêu đề bài viết');
            document.getElementById('title').focus();
            return false;
        }

        if (title.length < 10) {
            alert('Tiêu đề phải có ít nhất 10 ký tự');
            document.getElementById('title').focus();
            return false;
        }

        if (!content) {
            alert('Vui lòng nhập nội dung bài viết');
            document.getElementById('content').focus();
            return false;
        }

        if (content.length < 50) {
            alert('Nội dung phải có ít nhất 50 ký tự');
            document.getElementById('content').focus();
            return false;
        }

        return true;
    }

    // Auto-save functionality
    let autoSaveTimeout;

    function autoSave() {
        if (!hasUnsavedChanges) return;

        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            // Could implement auto-save to server
            console.log('Auto-saving changes...');

            // Save to localStorage as backup
            const formData = new FormData(document.getElementById('articleForm'));
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            localStorage.setItem('article_edit_{{ $article->id }}', JSON.stringify(data));
        }, 5000); // Save every 5 seconds
    }

    // Restore from localStorage if available
    function restoreAutoSave() {
        const saved = localStorage.getItem('article_edit_{{ $article->id }}');
        if (saved && confirm('Có dữ liệu tự động lưu từ phiên trước. Bạn có muốn khôi phục?')) {
            const data = JSON.parse(saved);

            Object.keys(data).forEach(key => {
                const field = document.querySelector(`[name="${key}"]`);
                if (field) {
                    field.value = data[key];
                    if (field.type === 'checkbox') {
                        field.checked = data[key] === '1';
                    }
                }
            });

            updateSlugPreview();
            updateReadingTime();
            previewImage(data.featured_image);
            togglePublishDate();
            trackChanges();

            // Clear the saved data
            localStorage.removeItem('article_edit_{{ $article->id }}');
        }
    }

    // Initialize comparison for change detection
    function initializeChangeDetection() {
        const inputs = document.querySelectorAll('input, textarea, select');

        inputs.forEach(input => {
            // Store original values
            input.setAttribute('data-original-value', input.value);

            input.addEventListener('input', function() {
                const originalValue = this.getAttribute('data-original-value');
                const currentValue = this.value;

                if (originalValue !== currentValue) {
                    trackChanges();
                }

                // Auto-save
                autoSave();
            });

            // For checkboxes
            if (input.type === 'checkbox') {
                input.setAttribute('data-original-checked', input.checked);
                input.addEventListener('change', function() {
                    const originalChecked = this.getAttribute('data-original-checked') === 'true';
                    if (originalChecked !== this.checked) {
                        trackChanges();
                    }
                    autoSave();
                });
            }
        });
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize
        storeOriginalData();
        updateSlugPreview();
        updateReadingTime();
        togglePublishDate();
        initializeChangeDetection();
        restoreAutoSave();

        // Drag and drop for image upload
        const uploadArea = document.getElementById('imageUploadArea');

        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.borderColor = 'var(--c-green-500)';
            this.style.backgroundColor = 'rgba(69, 255, 188, 0.1)';
        });

        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.style.borderColor = 'var(--c-gray-500)';
            this.style.backgroundColor = 'transparent';
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.borderColor = 'var(--c-gray-500)';
            this.style.backgroundColor = 'transparent';

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('imageFile').files = files;
                handleImageUpload(document.getElementById('imageFile'));
            }
        });

        // Form submission handlers
        document.getElementById('articleForm').addEventListener('submit', function(e) {
            const status = document.getElementById('status').value;

            if (status === 'published') {
                if (!confirm('Bạn có chắc muốn xuất bản bài viết này?')) {
                    e.preventDefault();
                    return false;
                }
            }

            // Clear auto-save data on successful submit
            localStorage.removeItem('article_edit_{{ $article->id }}');
            hasUnsavedChanges = false;

            // Show loading state
            const submitButtons = document.querySelectorAll('button[type="button"], button[type="submit"]');
            submitButtons.forEach(btn => {
                btn.disabled = true;
                const originalContent = btn.innerHTML;
                btn.innerHTML = '<i class="ph ph-spinner ph-spin"></i> Đang xử lý...';
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + S to save
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                saveDraft();
            }

            // Ctrl/Cmd + Enter to publish/save
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                e.preventDefault();
                const status = document.getElementById('status').value;
                if (status === 'draft' && document.querySelector('button[onclick="publishArticle()"]')) {
                    publishArticle();
                } else {
                    saveDraft();
                }
            }

            // Ctrl/Cmd + D to toggle draft
            if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
                e.preventDefault();
                document.getElementById('status').value = 'draft';
                togglePublishDate();
                trackChanges();
            }

            // Escape to cancel
            if (e.key === 'Escape') {
                if (hasUnsavedChanges) {
                    if (confirm('Bạn có thay đổi chưa được lưu. Bạn có chắc muốn hủy và quay lại?')) {
                        localStorage.removeItem('article_edit_{{ $article->id }}');
                        window.location.href = '{{ route('
                        admin.articles.index ') }}';
                    }
                } else {
                    window.location.href = '{{ route('
                    admin.articles.index ') }}';
                }
            }
        });

        // Warn before leaving with unsaved changes
        window.addEventListener('beforeunload', function(e) {
            if (hasUnsavedChanges) {
                const message = 'Bạn có thay đổi chưa được lưu. Bạn có chắc muốn rời khỏi trang này?';
                e.returnValue = message;
                return message;
            }
        });

        // Page visibility change - auto-save when user switches tabs
        document.addEventListener('visibilitychange', function() {
            if (document.hidden && hasUnsavedChanges) {
                autoSave();
            }
        });

        // Initial focus on title if it's empty, otherwise on content
        const titleField = document.getElementById('title');
        const contentField = document.getElementById('content');

        if (!titleField.value.trim()) {
            titleField.focus();
        } else {
            // Focus at the end of content
            contentField.focus();
            contentField.setSelectionRange(contentField.value.length, contentField.value.length);
        }
    });

    // Utility functions for enhanced UX
    function scrollToError() {
        const firstError = document.querySelector('.form-error');
        if (firstError) {
            firstError.scrollIntoView({
                behavior: 'smooth'
                , block: 'center'
            });
        }
    }

    // Show success message temporarily
    function showSuccessMessage(message) {
        const alert = document.createElement('div');
        alert.className = 'alert alert-success';
        alert.innerHTML = `<i class="ph ph-check-circle" style="margin-right: 0.5rem;"></i>${message}`;
        alert.style.position = 'fixed';
        alert.style.top = '20px';
        alert.style.right = '20px';
        alert.style.zIndex = '9999';
        alert.style.minWidth = '300px';

        document.body.appendChild(alert);

        setTimeout(() => {
            alert.remove();
        }, 3000);
    }

    // Enhanced form validation with visual feedback
    function validateFieldLive(field) {
        const value = field.value.trim();
        const fieldName = field.name;

        // Remove existing validation classes
        field.classList.remove('is-valid', 'is-invalid');

        let isValid = true;

        switch (fieldName) {
            case 'title':
                isValid = value.length >= 10 && value.length <= 255;
                break;
            case 'content':
                isValid = value.length >= 50;
                break;
            case 'slug':
                isValid = !value || /^[a-z0-9-]+$/.test(value);
                break;
            case 'featured_image':
                isValid = !value || isValidUrl(value);
                break;
        }

        field.classList.add(isValid ? 'is-valid' : 'is-invalid');

        return isValid;
    }

    function isValidUrl(string) {
        try {
            new URL(string);
            return true;
        } catch (_) {
            return false;
        }
    }

</script>
@endpush
