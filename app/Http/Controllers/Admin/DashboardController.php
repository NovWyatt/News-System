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
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
        ];

        // Get user's articles if editor
        if ($user->isEditor()) {
            $stats['my_articles'] = $user->total_articles;
            $stats['my_published'] = $user->published_articles_count;
            $stats['my_drafts'] = $user->draft_articles_count;
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
                    'author' => $article->author->name,
                    'created_at' => $article->created_at->format('d/m/Y H:i'),
                ];
            });

        // Recent activity logs (only for admin)
        $recentLogs = [];
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
                        'user' => $log->user ? $log->user->name : 'System',
                        'created_at' => $log->created_at->format('d/m/Y H:i'),
                    ];
                });
        }

        $dashboardData = [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('display_name'),
                'is_admin' => $user->isAdmin(),
                'is_editor' => $user->isEditor(),
            ],
            'stats' => $stats,
            'recent_articles' => $recentArticles,
            'recent_logs' => $recentLogs,
        ];

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'dashboard' => $dashboardData,
            ]);
        }

        return view('admin.dashboard', compact('dashboardData'));
    }
}
