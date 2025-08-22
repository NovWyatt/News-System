<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'featured_image',
        'status',
        'is_featured',
        'author_id',
        'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    // Relationships
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Helper methods
    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });

        static::updating(function ($article) {
            if ($article->isDirty('title') && empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByAuthor($query, $authorId)
    {
        return $query->where('author_id', $authorId);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('published_at', 'desc')->limit($limit);
    }

    public function scopeLatest($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    // Helper methods
    public function isPublished()
    {
        return $this->status === 'published' &&
               $this->published_at &&
               $this->published_at->isPast();
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isArchived()
    {
        return $this->status === 'archived';
    }

    public function canEdit($user = null)
    {
        $user = $user ?: auth()->user();

        if (!$user) return false;

        // Admin có thể edit tất cả
        if ($user->isAdmin()) return true;

        // Editor chỉ edit bài của mình hoặc có quyền manage_all
        if ($user->isEditor()) {
            return $this->author_id === $user->id ||
                   $user->hasPermission('articles.manage_all');
        }

        return false;
    }

    public function publish($publishedAt = null)
    {
        $this->update([
            'status' => 'published',
            'published_at' => $publishedAt ?: now(),
        ]);

        ActivityLog::log(
            'article.published',
            "Xuất bản bài viết: {$this->title}",
            $this
        );
    }

    public function archive()
    {
        $this->update(['status' => 'archived']);

        ActivityLog::log(
            'article.archived',
            "Lưu trữ bài viết: {$this->title}",
            $this
        );
    }

    public function makeDraft()
    {
        $this->update([
            'status' => 'draft',
            'published_at' => null,
        ]);

        ActivityLog::log(
            'article.drafted',
            "Chuyển bài viết về draft: {$this->title}",
            $this
        );
    }

    public function toggleFeatured()
    {
        $this->update(['is_featured' => !$this->is_featured]);

        $action = $this->is_featured ? 'featured' : 'unfeatured';
        ActivityLog::log(
            "article.{$action}",
            "Thay đổi trạng thái nổi bật: {$this->title}",
            $this
        );
    }

    // Accessors
    public function getExcerptAttribute($length = 150)
    {
        return Str::limit(strip_tags($this->content), $length);
    }

    public function getReadingTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $readingTime = ceil($wordCount / 200); // 200 words per minute
        return max(1, $readingTime); // Minimum 1 minute
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => '<span class="badge bg-secondary">Bản nháp</span>',
            'published' => '<span class="badge bg-success">Đã xuất bản</span>',
            'archived' => '<span class="badge bg-warning">Lưu trữ</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-light">Không xác định</span>';
    }

    public function getFormattedPublishedDateAttribute()
    {
        if (!$this->published_at) {
            return 'Chưa xuất bản';
        }

        return $this->published_at->format('d/m/Y H:i');
    }

    // Search scope
    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('title', 'LIKE', "%{$search}%")
              ->orWhere('content', 'LIKE', "%{$search}%");
        });
    }

    // Filter by status
    public function scopeByStatus($query, $status)
    {
        if (empty($status)) {
            return $query;
        }

        return $query->where('status', $status);
    }
}
