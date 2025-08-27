<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
        $this->middleware('permission:articles.view')->only(['index', 'show']);
        $this->middleware('permission:articles.create')->only(['create', 'store']);
        $this->middleware('permission:articles.edit')->only(['edit', 'update']);
        $this->middleware('permission:articles.delete')->only(['destroy']);
        $this->middleware('permission:articles.publish')->only(['publish', 'unpublish']);
    }

    /**
     * Display a listing of articles
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Validate filter parameters
        $filters = $request->validate([
            'search'   => 'nullable|string|max:255',
            'status'   => 'nullable|in:draft,published,archived',
            'author'   => 'nullable|integer|exists:users,id',
            'featured' => 'nullable|in:0,1',
            'sort'     => 'nullable|in:title,status,author,created_at,published_at',
            'order'    => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:10|max:100',
        ]);

        // Build query
        $query = Article::with(['author']);

        // Apply user permission filter
        if (! $user->hasPermission('articles.manage_all') && $user->isEditor()) {
            $query->where('author_id', $user->id);
        }

        // Apply search filter
        if (! empty($filters['search'])) {
            $query->search($filters['search']);
        }

        // Apply status filter
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Apply author filter (only if user can manage all articles)
        if (! empty($filters['author']) && $user->hasPermission('articles.manage_all')) {
            $query->where('author_id', $filters['author']);
        }

        // Apply featured filter
        if (isset($filters['featured'])) {
            $query->where('is_featured', (bool) $filters['featured']);
        }

        // Apply sorting
        $sortField = $filters['sort'] ?? 'created_at';
        $sortOrder = $filters['order'] ?? 'desc';

        if ($sortField === 'author') {
            $query->join('users', 'articles.author_id', '=', 'users.id')
                ->select('articles.*')
                ->orderBy('users.username', $sortOrder);
        } else {
            $query->orderBy($sortField, $sortOrder);
        }

        // Get articles with pagination
        $perPage  = $filters['per_page'] ?? 20;
        $articles = $query->paginate($perPage);

        // Transform articles data for JSON response
        $articlesData = $articles->getCollection()->map(function ($article) {
            return [
                'id'             => $article->id,
                'title'          => $article->title,
                'slug'           => $article->slug,
                'status'         => $article->status,
                'status_badge'   => $article->status_badge,
                'is_featured'    => $article->is_featured,
                'author'         => $article->author ? [
                    'id'    => $article->author->id,
                    'name'  => $article->author->username ?: $article->author->display_name,
                    'email' => $article->author->email,
                ]: null,
                'excerpt'        => $article->excerpt,
                'reading_time'   => $article->reading_time,
                'featured_image' => $article->featured_image,
                'published_at'   => $article->formatted_published_date,
                'created_at'     => $article->created_at->format('d/m/Y H:i'),
                'updated_at'     => $article->updated_at->format('d/m/Y H:i'),
                'can_edit'       => $article->canEdit(),
            ];
        });

        // Get authors for filter (only if user can manage all articles)
        $authors = collect();
        if ($user->hasPermission('articles.manage_all')) {
            $authors = User::withRole('editor')
                ->orWhere('id', $user->id)
                ->select('id', 'username', 'email')
                ->orderBy('username')
                ->get()
                ->map(function ($author) {
                    return [
                        'id'   => $author->id,
                        'name' => $author->username ?: $author->display_name,
                    ];
                });
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success'    => true,
                'articles'   => $articlesData,
                'pagination' => [
                    'current_page' => $articles->currentPage(),
                    'per_page'     => $articles->perPage(),
                    'total'        => $articles->total(),
                    'last_page'    => $articles->lastPage(),
                    'from'         => $articles->firstItem(),
                    'to'           => $articles->lastItem(),
                ],
                'filters'    => [
                    'authors'  => $authors,
                    'statuses' => [
                        ['value' => 'draft', 'label' => 'Bản nháp'],
                        ['value' => 'published', 'label' => 'Đã xuất bản'],
                        ['value' => 'archived', 'label' => 'Lưu trữ'],
                    ],
                ],
            ]);
        }

        return view('admin.articles.index', compact('articles', 'authors', 'filters'));
    }

    /**
     * Show the form for creating a new article
     */
    public function create()
    {
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data'    => [
                    'statuses' => [
                        ['value' => 'draft', 'label' => 'Bản nháp'],
                        ['value' => 'published', 'label' => 'Đã xuất bản'],
                        ['value' => 'archived', 'label' => 'Lưu trữ'],
                    ],
                ],
            ]);
        }

        return view('admin.articles.create');
    }

    /**
     * Store a newly created article
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'          => 'required|string|max:255',
            'slug'           => 'nullable|string|max:255|unique:articles,slug',
            'content'        => 'required|string',
            'featured_image' => 'nullable|url|max:500',
            'status'         => 'required|in:draft,published,archived',
            'is_featured'    => 'boolean',
            'published_at'   => 'nullable|date',
        ], [
            'title.required'     => 'Vui lòng nhập tiêu đề bài viết.',
            'title.max'          => 'Tiêu đề không được quá 255 ký tự.',
            'slug.unique'        => 'Slug này đã được sử dụng.',
            'slug.max'           => 'Slug không được quá 255 ký tự.',
            'content.required'   => 'Vui lòng nhập nội dung bài viết.',
            'featured_image.url' => 'URL hình ảnh không hợp lệ.',
            'status.required'    => 'Vui lòng chọn trạng thái bài viết.',
            'status.in'          => 'Trạng thái không hợp lệ.',
            'published_at.date'  => 'Ngày xuất bản không hợp lệ.',
        ]);

        $user = Auth::user();

        // Generate slug if not provided
        $slug = $request->slug;
        if (empty($slug)) {
            $slug         = Str::slug($request->title);
            $originalSlug = $slug;
            $counter      = 1;

            while (Article::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        // Handle published_at based on status
        $publishedAt = null;
        if ($request->status === 'published') {
            $publishedAt = $request->published_at ? $request->published_at : now();
        }

        $article = Article::create([
            'title'          => $request->title,
            'slug'           => $slug,
            'content'        => $request->content,
            'featured_image' => $request->featured_image,
            'status'         => $request->status,
            'is_featured'    => $request->boolean('is_featured'),
            'author_id'      => $user->id,
            'published_at'   => $publishedAt,
        ]);

        // Log activity
        ActivityLog::log(
            'article.created',
            "Tạo bài viết mới: {$article->title}",
            $article,
            [
                'status'      => $article->status,
                'is_featured' => $article->is_featured,
            ]
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tạo bài viết thành công!',
                'article' => [
                    'id'     => $article->id,
                    'title'  => $article->title,
                    'slug'   => $article->slug,
                    'status' => $article->status,
                ],
            ], 201);
        }

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Tạo bài viết thành công!');
    }

    /**
     * Display the specified article
     */
    public function show(Article $article)
    {
        $user = Auth::user();

        // Check if user can view this article
        if (! $user->hasPermission('articles.manage_all') && $article->author_id !== $user->id) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền xem bài viết này.',
                ], 403);
            }

            abort(403, 'Bạn không có quyền xem bài viết này.');
        }

        $article->load('author');

        $articleData = [
            'id'             => $article->id,
            'title'          => $article->title,
            'slug'           => $article->slug,
            'content'        => $article->content,
            'featured_image' => $article->featured_image,
            'status'         => $article->status,
            'status_badge'   => $article->status_badge,
            'is_featured'    => $article->is_featured,
            'author'         => [
                'id'    => $article->author->id,
                'name'  => $article->author->username ?: $article->author->display_name,
                'email' => $article->author->email,
            ],
            'excerpt'        => $article->excerpt,
            'reading_time'   => $article->reading_time,
            'published_at'   => $article->formatted_published_date,
            'created_at'     => $article->created_at->format('d/m/Y H:i:s'),
            'updated_at'     => $article->updated_at->format('d/m/Y H:i:s'),
            'can_edit'       => $article->canEdit(),
        ];

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'article' => $articleData,
            ]);
        }

        return view('admin.articles.show', compact('article', 'articleData'));
    }

    /**
     * Show the form for editing the specified article
     */
    public function edit(Article $article)
    {
        $user = Auth::user();

        // Check if user can edit this article
        if (! $article->canEdit()) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền chỉnh sửa bài viết này.',
                ], 403);
            }

            abort(403, 'Bạn không có quyền chỉnh sửa bài viết này.');
        }

        $article->load('author');

        $articleData = [
            'id'             => $article->id,
            'title'          => $article->title,
            'slug'           => $article->slug,
            'content'        => $article->content,
            'featured_image' => $article->featured_image,
            'status'         => $article->status,
            'is_featured'    => $article->is_featured,
            'published_at'   => $article->published_at?->format('Y-m-d\TH:i'),
        ];

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'article' => $articleData,
                'data'    => [
                    'statuses' => [
                        ['value' => 'draft', 'label' => 'Bản nháp'],
                        ['value' => 'published', 'label' => 'Đã xuất bản'],
                        ['value' => 'archived', 'label' => 'Lưu trữ'],
                    ],
                ],
            ]);
        }

        return view('admin.articles.edit', compact('article', 'articleData'));
    }

    /**
     * Update the specified article
     */
    public function update(Request $request, Article $article)
    {
        // Check if user can edit this article
        if (! $article->canEdit()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền chỉnh sửa bài viết này.',
                ], 403);
            }

            abort(403, 'Bạn không có quyền chỉnh sửa bài viết này.');
        }

        $request->validate([
            'title'          => 'required|string|max:255',
            'slug'           => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('articles', 'slug')->ignore($article->id),
            ],
            'content'        => 'required|string',
            'featured_image' => 'nullable|url|max:500',
            'status'         => 'required|in:draft,published,archived',
            'is_featured'    => 'boolean',
            'published_at'   => 'nullable|date',
        ], [
            'title.required'     => 'Vui lòng nhập tiêu đề bài viết.',
            'title.max'          => 'Tiêu đề không được quá 255 ký tự.',
            'slug.unique'        => 'Slug này đã được sử dụng.',
            'slug.max'           => 'Slug không được quá 255 ký tự.',
            'content.required'   => 'Vui lòng nhập nội dung bài viết.',
            'featured_image.url' => 'URL hình ảnh không hợp lệ.',
            'status.required'    => 'Vui lòng chọn trạng thái bài viết.',
            'status.in'          => 'Trạng thái không hợp lệ.',
            'published_at.date'  => 'Ngày xuất bản không hợp lệ.',
        ]);

        $oldData = [
            'title'       => $article->title,
            'status'      => $article->status,
            'is_featured' => $article->is_featured,
        ];

        // Generate slug if not provided or title changed
        $slug = $request->slug;
        if (empty($slug) || ($request->title !== $article->title && empty($slug))) {
            $slug         = Str::slug($request->title);
            $originalSlug = $slug;
            $counter      = 1;

            while (Article::where('slug', $slug)->where('id', '!=', $article->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        // Handle published_at based on status
        $publishedAt = $article->published_at;
        if ($request->status === 'published') {
            if (! $publishedAt || $request->published_at) {
                $publishedAt = $request->published_at ? $request->published_at : now();
            }
        } elseif ($request->status === 'draft') {
            $publishedAt = null;
        }

        $article->update([
            'title'          => $request->title,
            'slug'           => $slug,
            'content'        => $request->content,
            'featured_image' => $request->featured_image,
            'status'         => $request->status,
            'is_featured'    => $request->boolean('is_featured'),
            'published_at'   => $publishedAt,
        ]);

        // Log activity with changes
        $changes = [];
        if ($oldData['title'] !== $article->title) {
            $changes['title'] = ['from' => $oldData['title'], 'to' => $article->title];
        }
        if ($oldData['status'] !== $article->status) {
            $changes['status'] = ['from' => $oldData['status'], 'to' => $article->status];
        }
        if ($oldData['is_featured'] !== $article->is_featured) {
            $changes['is_featured'] = ['from' => $oldData['is_featured'], 'to' => $article->is_featured];
        }

        ActivityLog::log(
            'article.updated',
            "Cập nhật bài viết: {$article->title}",
            $article,
            ['changes' => $changes]
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật bài viết thành công!',
                'article' => [
                    'id'          => $article->id,
                    'title'       => $article->title,
                    'slug'        => $article->slug,
                    'status'      => $article->status,
                    'is_featured' => $article->is_featured,
                ],
            ]);
        }

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Cập nhật bài viết thành công!');
    }

    /**
     * Remove the specified article from storage
     */
    public function destroy(Article $article)
    {
        // Check if user can delete this article
        if (! $article->canEdit()) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền xóa bài viết này.',
                ], 403);
            }

            abort(403, 'Bạn không có quyền xóa bài viết này.');
        }

        $articleTitle = $article->title;
        $article->delete();

        // Log activity
        ActivityLog::log(
            'article.deleted',
            "Xóa bài viết: {$articleTitle}",
            null,
            ['deleted_article' => $articleTitle]
        );

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Xóa bài viết thành công!',
            ]);
        }

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Xóa bài viết thành công!');
    }

    /**
     * Publish an article
     */
    public function publish(Request $request, Article $article)
    {
        // Check permission
        if (! Auth::user()->hasPermission('articles.publish')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền xuất bản bài viết.',
                ], 403);
            }

            abort(403, 'Bạn không có quyền xuất bản bài viết.');
        }

        // Check if user can edit this article
        if (! $article->canEdit()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền xuất bản bài viết này.',
                ], 403);
            }

            abort(403, 'Bạn không có quyền xuất bản bài viết này.');
        }

        $publishedAt = $request->published_at ? $request->published_at : now();
        $article->publish($publishedAt);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Xuất bản bài viết thành công!',
                'article' => [
                    'id'           => $article->id,
                    'status'       => $article->status,
                    'published_at' => $article->formatted_published_date,
                ],
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Xuất bản bài viết thành công!');
    }

    /**
     * Unpublish an article (set to draft)
     */
    public function unpublish(Request $request, Article $article)
    {
        // Check permission
        if (! Auth::user()->hasPermission('articles.publish')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền hủy xuất bản bài viết.',
                ], 403);
            }

            abort(403, 'Bạn không có quyền hủy xuất bản bài viết.');
        }

        // Check if user can edit this article
        if (! $article->canEdit()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền hủy xuất bản bài viết này.',
                ], 403);
            }

            abort(403, 'Bạn không có quyền hủy xuất bản bài viết này.');
        }

        $article->makeDraft();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Hủy xuất bản bài viết thành công!',
                'article' => [
                    'id'           => $article->id,
                    'status'       => $article->status,
                    'published_at' => null,
                ],
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Hủy xuất bản bài viết thành công!');
    }

    /**
     * Archive an article
     */
    public function archive(Request $request, Article $article)
    {
        // Check if user can edit this article
        if (! $article->canEdit()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền lưu trữ bài viết này.',
                ], 403);
            }

            abort(403, 'Bạn không có quyền lưu trữ bài viết này.');
        }

        $article->archive();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Lưu trữ bài viết thành công!',
                'article' => [
                    'id'     => $article->id,
                    'status' => $article->status,
                ],
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Lưu trữ bài viết thành công!');
    }

    /**
     * Toggle featured status of an article
     */
    public function toggleFeatured(Request $request, Article $article)
    {
        // Check if user can edit this article
        if (! $article->canEdit()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền thay đổi trạng thái nổi bật của bài viết này.',
                ], 403);
            }

            abort(403, 'Bạn không có quyền thay đổi trạng thái nổi bật của bài viết này.');
        }

        $article->toggleFeatured();

        $message = $article->is_featured ?
        'Đã đánh dấu bài viết là nổi bật!' :
        'Đã hủy đánh dấu nổi bật cho bài viết!';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'article' => [
                    'id'          => $article->id,
                    'is_featured' => $article->is_featured,
                ],
            ]);
        }

        return redirect()
            ->back()
            ->with('success', $message);
    }

    /**
     * Get articles for API or AJAX requests
     */
    public function api(Request $request)
    {
        return $this->index($request);
    }
}
