@forelse($articles as $article)
    <!-- Post preview-->
    <div class="post-preview">
        <a href="{{ route('article.show', $article->slug) }}">
            <h2 class="post-title">{{ $article->title }}</h2>
            @if($article->excerpt)
                <h3 class="post-subtitle">{{ $article->excerpt }}</h3>
            @endif
        </a>
        <p class="post-meta">
            Posted by
            <a href="#!">{{ $article->author_name }}</a>
            on {{ $article->formatted_published_date }}
        </p>
    </div>
    <!-- Divider-->
    <hr class="my-4" />
@empty
    <div class="text-center text-muted py-5">
        <h3>Chưa có bài viết nào</h3>
        <p>Hệ thống chưa có bài viết được xuất bản.</p>
    </div>
@endforelse
