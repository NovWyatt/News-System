{{-- resources/views/article.blade.php --}}
@extends('layouts.app')

@section('title', $article->title)

@section('content')
    <!-- Header với background image -->
    <header class="masthead" style="background-image: url('{{ $article->featured_image ?: asset('assets/img/post-bg.jpg') }}')">
        <div class="container position-relative px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div class="post-heading">
                        <h1>{{ $article->title }}</h1>
                        @if($article->excerpt)
                            <h2 class="subheading">{{ $article->excerpt }}</h2>
                        @endif
                        <span class="meta">
                            Posted by
                            <a href="#!">{{ $article->author_name }}</a>
                            on {{ $article->formatted_published_date }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Post Content-->
    <article class="mb-4">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <!-- Article Content -->
                    <div class="article-content">
                        {!! $article->content !!}
                    </div>

                    <!-- Article Footer / Meta -->
                    <div class="article-footer mt-5 pt-4 border-top">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="article-info mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $article->reading_time }} phút đọc
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $article->published_at->format('d/m/Y') }}
                                </small>
                            </div>

                            <div class="article-share">
                                <span class="text-muted me-2">Chia sẻ:</span>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                                   target="_blank"
                                   class="btn btn-outline-primary btn-sm me-1"
                                   title="Chia sẻ lên Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($article->title) }}"
                                   target="_blank"
                                   class="btn btn-outline-info btn-sm me-1"
                                   title="Chia sẻ lên Twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <button class="btn btn-outline-secondary btn-sm"
                                        onclick="copyToClipboard('{{ request()->fullUrl() }}')"
                                        title="Sao chép link">
                                    <i class="fas fa-link"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Navigation -->
                        <div class="article-navigation mt-4">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    @if($previousArticle = \App\Models\Article::published()->where('published_at', '<', $article->published_at)->latest('published_at')->first())
                                        <a href="{{ route('article.show', $previousArticle->slug) }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-arrow-left me-1"></i>
                                            Bài trước
                                        </a>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-2 text-md-end">
                                    @if($nextArticle = \App\Models\Article::published()->where('published_at', '>', $article->published_at)->oldest('published_at')->first())
                                        <a href="{{ route('article.show', $nextArticle->slug) }}" class="btn btn-outline-secondary btn-sm">
                                            Bài sau
                                            <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Back to Home -->
                        <div class="text-center mt-4">
                            <a href="{{ route('home') }}" class="btn btn-primary">
                                <i class="fas fa-home me-1"></i>
                                Quay về trang chủ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>
@endsection

@push('scripts')
<script>
function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(function() {
            showToast('Link đã được sao chép!', 'success');
        }, function() {
            fallbackCopyTextToClipboard(text);
        });
    } else {
        fallbackCopyTextToClipboard(text);
    }
}

function fallbackCopyTextToClipboard(text) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
        document.execCommand('copy');
        showToast('Link đã được sao chép!', 'success');
    } catch (err) {
        showToast('Không thể sao chép link!', 'error');
    }

    document.body.removeChild(textArea);
}

function showToast(message, type = 'success') {
    // Simple toast notification
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 250px;';
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close ms-2" onclick="this.parentElement.remove()"></button>
    `;

    document.body.appendChild(toast);

    // Auto remove after 3 seconds
    setTimeout(() => {
        if (toast.parentElement) {
            toast.remove();
        }
    }, 3000);
}

// Smooth scroll for internal links
document.addEventListener('DOMContentLoaded', function() {
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
});
</script>
@endpush

@push('styles')
<style>
/* Article Styles */
.post-heading h1 {
    font-size: 3rem;
    font-weight: 800;
    line-height: 1.1;
    margin-bottom: 1rem;
}

.post-heading h2.subheading {
    font-size: 1.5rem;
    font-weight: 300;
    line-height: 1.4;
    margin-bottom: 1rem;
    opacity: 0.9;
}

.post-heading .meta {
    font-size: 1.1rem;
    font-weight: 300;
}

.post-heading .meta a {
    color: white;
    text-decoration: none;
}

.post-heading .meta a:hover {
    text-decoration: underline;
}

.article-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
}

.article-content h1,
.article-content h2,
.article-content h3 {
    margin-top: 2.5rem;
    margin-bottom: 1.5rem;
    font-weight: 700;
    line-height: 1.2;
}

.article-content h2.section-heading {
    font-size: 1.8rem;
    margin-top: 3rem;
    margin-bottom: 1.5rem;
}

.article-content p {
    margin-bottom: 1.8rem;
    text-align: justify;
}

.article-content blockquote {
    font-size: 1.2rem;
    font-style: italic;
    margin: 2rem 0;
    padding: 1rem 2rem;
    border-left: 4px solid #007bff;
    background-color: #f8f9fa;
}

.article-content img {
    max-width: 100%;
    height: auto;
    margin: 2rem auto;
    display: block;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.article-content .caption {
    display: block;
    text-align: center;
    font-size: 0.9rem;
    margin-top: 0.5rem;
    margin-bottom: 2rem;
}

.article-footer {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    margin-top: 3rem;
}

.article-share .btn {
    min-width: 40px;
    height: 40px;
}

.article-navigation {
    border-top: 1px solid #dee2e6;
    padding-top: 1rem;
    margin-top: 1rem;
}

/* Masthead overlay for better text readability */
.masthead {
    position: relative;
}

.masthead::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
}

.masthead .container {
    position: relative;
    z-index: 2;
}

/* Responsive Design */
@media (max-width: 768px) {
    .post-heading h1 {
        font-size: 2rem;
    }

    .post-heading h2.subheading {
        font-size: 1.2rem;
    }

    .article-content {
        font-size: 1rem;
    }

    .article-content blockquote {
        margin: 1.5rem -1rem;
        padding: 1rem;
        font-size: 1.1rem;
    }

    .article-footer {
        padding: 1rem;
    }

    .d-flex.justify-content-between {
        flex-direction: column;
        align-items: stretch !important;
    }

    .article-share {
        margin-top: 1rem;
        text-align: center;
    }
}

/* Print Styles */
@media print {
    .article-footer,
    .btn {
        display: none !important;
    }

    .article-content {
        font-size: 12pt;
        line-height: 1.5;
    }
}
</style>
@endpush
