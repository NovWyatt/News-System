@extends('layouts.app')
@section('content')
    <header class="masthead" style="background-image: url('assets/img/home-bg.jpg')">
        <div class="container position-relative px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div class="site-heading">
                        <h1>Wyatt Article</h1>
                        <span class="subheading">A Blog by Wyatt</span>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Main Content-->
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <!-- Articles Container -->
                <div id="articles-container">
                    @include('partials.article-list', ['articles' => $articles])
                </div>

                <!-- Load More Button -->
                @if($articles->hasMorePages())
                    <div class="d-flex justify-content-end mb-4" id="load-more-container">
                        <button id="load-more-btn" class="btn btn-primary text-uppercase"
                            data-next-page="{{ $articles->currentPage() + 1 }}">
                            <span id="load-more-text">Older Posts →</span>
                            <span id="load-more-spinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status"
                                aria-hidden="true"></span>
                        </button>
                    </div>
                @endif

                <!-- No More Posts Message -->
                <div id="no-more-posts" class="text-center text-muted mt-4 d-none">
                    <p>Đã hiển thị tất cả bài viết</p>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const loadMoreBtn = document.getElementById('load-more-btn');
                const articlesContainer = document.getElementById('articles-container');
                const loadMoreContainer = document.getElementById('load-more-container');
                const noMorePosts = document.getElementById('no-more-posts');
                const loadMoreText = document.getElementById('load-more-text');
                const loadMoreSpinner = document.getElementById('load-more-spinner');

                if (loadMoreBtn) {
                    loadMoreBtn.addEventListener('click', function () {
                        const nextPage = this.getAttribute('data-next-page');

                        // Show loading state
                        loadMoreText.textContent = 'Đang tải...';
                        loadMoreSpinner.classList.remove('d-none');
                        loadMoreBtn.disabled = true;

                        // Make AJAX request
                        fetch(`/api/articles/load-more?page=${nextPage}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success && data.articles.length > 0) {
                                    // Create articles HTML
                                    let articlesHtml = '';
                                    data.articles.forEach(article => {
                                        articlesHtml += createArticleHtml(article);
                                    });

                                    // Append new articles
                                    articlesContainer.insertAdjacentHTML('beforeend', articlesHtml);

                                    // Update next page
                                    if (data.hasMore) {
                                        loadMoreBtn.setAttribute('data-next-page', data.nextPage);
                                        loadMoreText.textContent = 'Older Posts →';
                                        loadMoreSpinner.classList.add('d-none');
                                        loadMoreBtn.disabled = false;
                                    } else {
                                        // No more posts
                                        loadMoreContainer.classList.add('d-none');
                                        noMorePosts.classList.remove('d-none');
                                    }
                                } else {
                                    // No more posts
                                    loadMoreContainer.classList.add('d-none');
                                    noMorePosts.classList.remove('d-none');
                                }
                            })
                            .catch(error => {
                                console.error('Error loading more articles:', error);

                                // Reset button state
                                loadMoreText.textContent = 'Older Posts →';
                                loadMoreSpinner.classList.add('d-none');
                                loadMoreBtn.disabled = false;

                                // Show error message
                                alert('Có lỗi xảy ra khi tải bài viết. Vui lòng thử lại.');
                            });
                    });
                }

                function createArticleHtml(article) {
                    const subtitle = article.excerpt ? `<h3 class="post-subtitle">${article.excerpt}</h3>` : '';

                    return `
                    <!-- Post preview-->
                    <div class="post-preview">
                        <a href="${article.url}">
                            <h2 class="post-title">${article.title}</h2>
                            ${subtitle}
                        </a>
                        <p class="post-meta">
                            Posted by
                            <a href="#!">${article.author_name}</a>
                            on ${article.published_at}
                        </p>
                    </div>
                    <!-- Divider-->
                    <hr class="my-4" />
                `;
                }
            });
        </script>
    @endpush
@endsection
