<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Hiển thị trang chủ với danh sách bài viết
     */
    public function index(Request $request)
    {
        // Số bài viết hiển thị ban đầu và mỗi lần load more
        $perPage = 4;

        // Lấy bài viết đã xuất bản, sắp xếp theo ngày tạo (mới nhất trước)
        $articles = Article::published()
            ->with('author')
            ->select('id', 'title', 'slug', 'content', 'featured_image', 'author_id', 'published_at', 'created_at')
            ->latest('published_at')
            ->paginate($perPage);

        // Nếu là AJAX request (từ nút "Older Posts")
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'articles' => $articles->items(),
                'hasMore' => $articles->hasMorePages(),
                'nextPage' => $articles->currentPage() + 1,
                'html' => view('partials.article-list', compact('articles'))->render()
            ]);
        }

        // Trả về view trang chủ
        return view('home', compact('articles'));
    }

    /**
     * API endpoint cho AJAX load more articles
     */
    public function loadMore(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = 4;

        $articles = Article::published()
            ->with('author')
            ->select('id', 'title', 'slug', 'content', 'featured_image', 'author_id', 'published_at', 'created_at')
            ->latest('published_at')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'articles' => $articles->map(function($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'excerpt' => $article->excerpt,
                    'content' => $article->content,
                    'featured_image' => $article->featured_image,
                    'author_name' => $article->author->username ?: $article->author->email,
                    'published_at' => $article->published_at->format('F j, Y'),
                    'url' => route('article.show', $article->slug),
                ];
            }),
            'hasMore' => $articles->hasMorePages(),
            'nextPage' => $articles->currentPage() + 1,
            'currentPage' => $articles->currentPage(),
            'totalPages' => $articles->lastPage(),
        ]);
    }
}
