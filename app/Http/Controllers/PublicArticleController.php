<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class PublicArticleController extends Controller
{
    /**
     * Hiển thị chi tiết bài viết công khai
     */
    public function show(Article $article)
    {
        // Chỉ cho phép xem bài viết đã xuất bản
        if ($article->status !== 'published') {
            abort(404);
        }

        // Load thông tin author
        $article->load('author');

        return view('article', compact('article'));
    }
}
