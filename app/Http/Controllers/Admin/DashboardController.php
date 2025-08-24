<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Show admin dashboard
     */
    public function index()
    {
        $user = auth()->user();

        // Get statistics
        $stats = [
            'total_articles' => Article::count(),
            'published_articles' => Article::published()->count(),
            'draft_articles' => Article::draft()->count(),
            'featured_articles' => Article::featured()->count(),
        ];

        // Add admin-only stats
        if ($user->isAdmin()) {
            $stats['total_users'] = User::count();
            $stats['active_users'] = User::where('is_active', true)->count();
        }

        // Get user's articles if editor
        if ($user->isEditor()) {
            $stats['my_articles'] = $user->articles()->count();
            $stats['my_published'] = $user->articles()->where('status', 'published')->count();
            $stats['my_drafts'] = $user->articles()->where('status', 'draft')->count();
        }

        // Recent articles
        $recentArticles = Article::with('author')
            ->when($user->isEditor() && !$user->hasPermission('articles.manage_all'), function ($query) use ($user) {
                return $query->where('author_id', $user->id);
            })
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'status' => $article->status,
                    'author' => $article->author->username ?: $article->author->email,
                    'created_at' => $article->created_at->format('d/m/Y H:i'),
                ];
            });

        // Recent activity logs (only for admin)
        $recentLogs = collect();
        if ($user->isAdmin()) {
            $recentLogs = ActivityLog::with('user')
                ->latest()
                ->limit(10)
                ->get()
                ->map(function ($log) {
                    return [
                        'id' => $log->id,
                        'action' => $log->action,
                        'description' => $log->description,
                        'user' => $log->user ? ($log->user->username ?: $log->user->email) : 'System',
                        'created_at' => $log->created_at->format('d/m/Y H:i'),
                    ];
                });
        }

        $dashboardData = [
            'user' => [
                'email' => $user->email,
                'username' => $user->username,
                'display_name' => $user->username ?: explode('@', $user->email)[0],
                'roles' => $user->roles->pluck('display_name'),
                'is_admin' => $user->isAdmin(),
                'is_editor' => $user->isEditor(),
                'last_login_at' => $user->last_login_at?->diffForHumans(),
            ],
            'stats' => $stats,
            'recentArticles' => $recentArticles,
            'recentLogs' => $recentLogs,
        ];

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'dashboard' => $dashboardData,
            ]);
        }

        return view('admin.dashboard', $dashboardData);
    }
}
