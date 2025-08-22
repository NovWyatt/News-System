<?php
namespace App\Services;

use App\Models\Article;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ArticleService
{
    public function createArticle(array $data, $user = null)
    {
        $user = $user ?: auth()->user();

        $data['author_id'] = $user->id;

        if (isset($data['featured_image']) && $data['featured_image'] instanceof UploadedFile) {
            $data['featured_image'] = $this->uploadFeaturedImage($data['featured_image']);
        }

        $article = Article::create($data);

        ActivityLog::log(
            'article.created',
            "Tạo bài viết mới: {$article->title}",
            $article
        );

        return $article;
    }

    public function updateArticle(Article $article, array $data)
    {
        if (isset($data['featured_image']) && $data['featured_image'] instanceof UploadedFile) {
            // Delete old image
            if ($article->featured_image) {
                Storage::disk('public')->delete($article->featured_image);
            }

            $data['featured_image'] = $this->uploadFeaturedImage($data['featured_image']);
        }

        $article->update($data);

        ActivityLog::log(
            'article.updated',
            "Cập nhật bài viết: {$article->title}",
            $article
        );

        return $article;
    }

    public function deleteArticle(Article $article)
    {
        // Delete featured image
        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }

        ActivityLog::log(
            'article.deleted',
            "Xóa bài viết: {$article->title}",
            $article
        );

        $article->delete();
    }

    private function uploadFeaturedImage(UploadedFile $file)
    {
        return $file->store('articles', 'public');
    }
}
