@extends('layouts.admin')

@section('title', 'Tạo bài viết mới')
@section('page-description', 'Tạo và xuất bản bài viết mới cho website')

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

    /* Status indicator */
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
        background-color: var(--c-orange-500);
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
                <span class="status-dot"></span>
                Bản nháp
            </div>
        </div>
        <div class="actions-right">
            <button type="button" class="btn-outline" onclick="saveAsDraft()">
                <i class="ph ph-floppy-disk"></i>
                Lưu nháp
            </button>
            @if(auth()->user()->hasPermission('articles.publish'))
            <button type="button" class="btn-primary" onclick="publishArticle()">
                <i class="ph ph-upload-simple"></i>
                Xuất bản
            </button>
            @endif
        </div>
    </div>
</div>

<!-- Main Form -->
<form id="articleForm" action="{{ route('admin.articles.store') }}" method="POST">
    @csrf

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
                <input type="text" name="title" id="title" class="form-input" placeholder="Nhập tiêu đề bài viết..." value="{{ old('title') }}" required onkeyup="updateSlug(); updateReadingTime();">
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
                <input type="text" name="slug" id="slug" class="form-input" placeholder="duoc-tao-tu-dong-tu-tieu-de" value="{{ old('slug') }}" onkeyup="updateSlugPreview();">
                <div class="form-help">
                    Để trống để tự động tạo từ tiêu đề. Chỉ sử dụng chữ cái, số và dấu gạch ngang.
                </div>
                <div id="slugPreview" class="slug-preview" style="display: none;">
                    <span class="slug-preview-url">{{ url('/') }}/</span>
                    <span class="slug-preview-slug" id="slugPreviewText"></span>
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
                <select name="status" id="status" class="form-select">
                    <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Bản nháp</option>
                    @if(auth()->user()->hasPermission('articles.publish'))
                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Xuất bản ngay</option>
                    @endif
                    <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Lưu trữ</option>
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
                <span id="readingTimeText">0 phút đọc</span>
            </div>
        </div>

        <div class="form-group full-width">
            <label class="form-label" for="content">
                <i class="ph ph-text-align-justify"></i>
                Nội dung
                <span class="required">*</span>
            </label>
            <textarea name="content" id="content" class="form-textarea large" placeholder="Nhập nội dung bài viết... Hỗ trợ Markdown và HTML" required onkeyup="updateReadingTime();">{{ old('content') }}</textarea>
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
                <input type="url" name="featured_image" id="featured_image" class="form-input" placeholder="https://example.com/image.jpg" value="{{ old('featured_image') }}" onchange="previewImage(this.value)">
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

            <div id="imageUploadArea" class="image-upload" onclick="document.getElementById('imageFile').click();">
                <input type="file" id="imageFile" accept="image/*" style="display: none;" onchange="handleImageUpload(this)">
                <div class="image-upload-content" id="uploadContent">
                    <i class="ph ph-cloud-arrow-up image-upload-icon"></i>
                    <div class="image-upload-text">
                        <strong>Click để upload hình ảnh</strong><br>
                        Hoặc kéo thả hình ảnh vào đây
                    </div>
                    <div style="font-size: 0.75rem; color: var(--c-text-tertiary);">
                        Hỗ trợ: JPG, PNG, GIF (tối đa 5MB)
                    </div>
                </div>
                <img id="imagePreview" class="image-preview" style="display: none;" alt="Preview">
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
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" class="checkbox-input" {{ old('is_featured') ? 'checked' : '' }}>
                    <label for="is_featured" class="checkbox-label">
                        <strong>Bài viết nổi bật</strong><br>
                        <small>Hiển thị ở vị trí nổi bật trên trang chủ</small>
                    </label>
                </div>
            </div>

            <div class="form-group" id="publishDateGroup" style="display: none;">
                <label class="form-label" for="published_at">
                    <i class="ph ph-calendar"></i>
                    Thời gian xuất bản
                </label>
                <input type="datetime-local" name="published_at" id="published_at" class="form-input" value="{{ old('published_at') }}">
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
                        <span id="wordCount">0</span>
                    </div>
                    <div>
                        <strong>Số ký tự:</strong>
                        <span id="charCount">0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div style="display: flex; justify-content: center; gap: 1rem; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--c-gray-600);">
        <a href="{{ route('admin.articles.index') }}" class="btn-secondary">
            <i class="ph ph-x"></i>
            Hủy bỏ
        </a>
        <button type="button" class="btn-outline" onclick="saveAsDraft()">
            <i class="ph ph-floppy-disk"></i>
            Lưu bản nháp
        </button>
        @if(auth()->user()->hasPermission('articles.publish'))
        <button type="button" class="btn-primary" onclick="publishArticle()">
            <i class="ph ph-upload-simple"></i>
            Xuất bản ngay
        </button>
        @endif
    </div>
</form>

<!-- CSRF Token for AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('scripts')
<script>
    // CSRF Token setup
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Auto-generate slug from title
    function updateSlug() {
        const title = document.getElementById('title').value;
        const slugField = document.getElementById('slug');

        if (title && !slugField.value) {
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
        const preview = document.getElementById('slugPreview');
        const previewText = document.getElementById('slugPreviewText');

        if (slug) {
            previewText.textContent = slug;
            preview.style.display = 'flex';
        } else {
            preview.style.display = 'none';
        }
    }

    // Calculate reading time
    function updateReadingTime() {
        const title = document.getElementById('title').value || '';
        const content = document.getElementById('content').value || '';
        const text = title + ' ' + content;

        const wordCount = text.trim() ? text.trim().split(/\s+/).length : 0;
        const charCount = text.length;
        const readingTime = Math.max(1, Math.ceil(wordCount / 200));

        document.getElementById('wordCount').textContent = wordCount.toLocaleString();
        document.getElementById('charCount').textContent = charCount.toLocaleString();
        document.getElementById('readingTimeText').textContent = readingTime + ' phút đọc';
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
    function saveAsDraft() {
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

    // Form validation
    function validateForm() {
        const title = document.getElementById('title').value.trim();
        const content = document.getElementById('content').value.trim();

        if (!title) {
            alert('Vui lòng nhập tiêu đề bài viết');
            document.getElementById('title').focus();
            return false;
        }

        if (!content) {
            alert('Vui lòng nhập nội dung bài viết');
            document.getElementById('content').focus();
            return false;
        }

        return true;
    }

    // Auto-save functionality (optional)
    let autoSaveTimeout;

    function autoSave() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            const title = document.getElementById('title').value.trim();
            const content = document.getElementById('content').value.trim();

            if (title || content) {
                // Could implement auto-save to localStorage or server
                console.log('Auto-saving draft...');
            }
        }, 10000); // Save every 10 seconds
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize
        updateSlugPreview();
        updateReadingTime();
        togglePublishDate();

        // Status change listener
        document.getElementById('status').addEventListener('change', togglePublishDate);

        // Auto-save listeners
        document.getElementById('title').addEventListener('input', autoSave);
        document.getElementById('content').addEventListener('input', autoSave);

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

        // Form submission confirmation
        document.getElementById('articleForm').addEventListener('submit', function(e) {
            const status = document.getElementById('status').value;

            if (status === 'published') {
                if (!confirm('Bạn có chắc muốn xuất bản bài viết này ngay lập tức?')) {
                    e.preventDefault();
                    return false;
                }
            }

            // Show loading state
            const submitButtons = document.querySelectorAll('button[type="button"], button[type="submit"]');
            submitButtons.forEach(btn => {
                btn.disabled = true;
                btn.innerHTML = '<i class="ph ph-spinner ph-spin"></i> Đang xử lý...';
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + S to save as draft
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                saveAsDraft();
            }

            // Ctrl/Cmd + Enter to publish
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                e.preventDefault();
                if (document.querySelector('button[onclick="publishArticle()"]')) {
                    publishArticle();
                }
            }

            // Escape to cancel
            if (e.key === 'Escape') {
                if (confirm('Bạn có chắc muốn hủy và quay lại? Những thay đổi chưa lưu sẽ bị mất.')) {
                    window.location.href = '{{ route('admin.articles.index') }}';
                }
            }
        });

        // Warn before leaving with unsaved changes
        let hasUnsavedChanges = false;
        const inputs = document.querySelectorAll('input, textarea, select');

        inputs.forEach(input => {
            input.addEventListener('input', function() {
                hasUnsavedChanges = true;
            });
        });

        window.addEventListener('beforeunload', function(e) {
            if (hasUnsavedChanges) {
                const message = 'Bạn có thay đổi chưa được lưu. Bạn có chắc muốn rời khỏi trang này?';
                e.returnValue = message;
                return message;
            }
        });

        // Remove warning when form is submitted
        document.getElementById('articleForm').addEventListener('submit', function() {
            hasUnsavedChanges = false;
        });

        // Initial focus
        document.getElementById('title').focus();
    });

    // Utility functions
    function scrollToError() {
        const firstError = document.querySelector('.form-error');
        if (firstError) {
            firstError.scrollIntoView({
                behavior: 'smooth'
                , block: 'center'
            });
        }
    }

    // Enhanced form validation with visual feedback
    function validateField(fieldName) {
        const field = document.getElementById(fieldName);
        const value = field.value.trim();

        field.style.borderColor = 'var(--c-gray-600)';
        field.style.boxShadow = 'none';

        // Remove existing error messages
        const existingError = field.parentNode.querySelector('.validation-error');
        if (existingError) {
            existingError.remove();
        }

        let isValid = true;
        let errorMessage = '';

        switch (fieldName) {
            case 'title':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Vui lòng nhập tiêu đề bài viết';
                } else if (value.length < 10) {
                    isValid = false;
                    errorMessage = 'Tiêu đề phải có ít nhất 10 ký tự';
                } else if (value.length > 255) {
                    isValid = false;
                    errorMessage = 'Tiêu đề không được quá 255 ký tự';
                }
                break;

            case 'content':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Vui lòng nhập nội dung bài viết';
                } else if (value.length < 50) {
                    isValid = false;
                    errorMessage = 'Nội dung phải có ít nhất 50 ký tự';
                }
                break;

            case 'slug':
                if (value && !/^[a-z0-9-]+$/.test(value)) {
                    isValid = false;
                    errorMessage = 'Slug chỉ được chứa chữ cái thường, số và dấu gạch ngang';
                }
                break;

            case 'featured_image':
                if (value && !isValidUrl(value)) {
                    isValid = false;
                    errorMessage = 'URL hình ảnh không hợp lệ';
                }
                break;
        }

        if (!isValid) {
            field.style.borderColor = 'var(--c-red-500)';
            field.style.boxShadow = '0 0 0 2px rgba(255, 71, 87, 0.2)';

            const errorDiv = document.createElement('div');
            errorDiv.className = 'form-error validation-error';
            errorDiv.innerHTML = `<i class="ph ph-warning-circle"></i> ${errorMessage}`;
            field.parentNode.appendChild(errorDiv);
        } else {
            field.style.borderColor = 'var(--c-green-500)';
            field.style.boxShadow = '0 0 0 2px rgba(69, 255, 188, 0.2)';
        }

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

    // Enhanced validateForm function
    function validateForm() {
        const fields = ['title', 'content', 'slug', 'featured_image'];
        let isFormValid = true;

        fields.forEach(field => {
            if (!validateField(field)) {
                isFormValid = false;
            }
        });

        if (!isFormValid) {
            scrollToError();
        }

        return isFormValid;
    }

    // Add live validation
    document.addEventListener('DOMContentLoaded', function() {
        const fieldsToValidate = ['title', 'content', 'slug', 'featured_image'];

        fieldsToValidate.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (field) {
                field.addEventListener('blur', () => validateField(fieldName));

                // For title and content, also validate on input (with debounce)
                if (['title', 'content'].includes(fieldName)) {
                    let validationTimeout;
                    field.addEventListener('input', function() {
                        clearTimeout(validationTimeout);
                        validationTimeout = setTimeout(() => validateField(fieldName), 1000);
                    });
                }
            }
        });
    });

</script>
@endpush
