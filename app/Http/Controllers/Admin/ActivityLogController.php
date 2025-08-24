<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
        $this->middleware('permission:system.view_logs');
    }

    /**
     * Display activity logs
     */
    public function index(Request $request)
    {
        // Validate and get filter parameters
        $filters = $request->validate([
            'action' => 'nullable|string|max:100',
            'user_id' => 'nullable|integer|exists:users,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'search' => 'nullable|string|max:255',
            'per_page' => 'nullable|integer|min:10|max:100',
        ]);

        // Build query
        $query = ActivityLog::with(['user'])
            ->latest();

        // Apply filters
        if (!empty($filters['action'])) {
            $query->where('action', 'LIKE', '%' . $filters['action'] . '%');
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('description', 'LIKE', "%{$search}%")
                  ->orWhere('action', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('email', 'LIKE', "%{$search}%")
                               ->orWhere('username', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Get logs with pagination
        $perPage = $filters['per_page'] ?? 20;
        $logs = $query->paginate($perPage)->withQueryString();

        // Get statistics
        $stats = $this->getLogStatistics($filters);

        // Get filter options
        $filterOptions = $this->getFilterOptions();

        // Prepare data for view
        $data = [
            'logs' => $logs,
            'stats' => $stats,
            'filters' => $filters,
            'filterOptions' => $filterOptions,
            'currentFilters' => array_filter($filters),
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        }

        return view('admin.logs.index', $data);
    }

    /**
     * Delete old logs (cleanup)
     */
    public function cleanup(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365',
            'confirm' => 'required|accepted',
        ], [
            'days.required' => 'Vui lòng nhập số ngày.',
            'days.min' => 'Số ngày phải lớn hơn 0.',
            'days.max' => 'Số ngày không được vượt quá 365.',
            'confirm.accepted' => 'Vui lòng xác nhận việc xóa logs.',
        ]);

        $days = $request->days;
        $cutoffDate = Carbon::now()->subDays($days);

        $deletedCount = ActivityLog::where('created_at', '<', $cutoffDate)->delete();

        // Log cleanup action
        ActivityLog::log(
            'system.logs_cleanup',
            "Đã xóa {$deletedCount} nhật ký cũ hơn {$days} ngày",
            null,
            [
                'deleted_count' => $deletedCount,
                'cutoff_date' => $cutoffDate->toDateString(),
                'days' => $days,
            ]
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Đã xóa thành công {$deletedCount} nhật ký cũ.",
                'deleted_count' => $deletedCount,
            ]);
        }

        return back()->with('success', "Đã xóa thành công {$deletedCount} nhật ký cũ.");
    }

    /**
     * Export logs to CSV
     */
    public function export(Request $request)
    {
        $filters = $request->validate([
            'action' => 'nullable|string|max:100',
            'user_id' => 'nullable|integer|exists:users,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
        ]);

        // Build export query (same as index but without pagination)
        $query = ActivityLog::with(['user'])->latest();

        // Apply same filters as index
        if (!empty($filters['action'])) {
            $query->where('action', 'LIKE', '%' . $filters['action'] . '%');
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $logs = $query->limit(10000)->get(); // Limit for performance

        // Generate CSV content
        $csvContent = $this->generateCsvContent($logs);

        // Log export action
        ActivityLog::log(
            'system.logs_exported',
            "Xuất {$logs->count()} nhật ký ra file CSV",
            null,
            ['filters' => array_filter($filters)]
        );

        $filename = 'activity_logs_' . date('Y-m-d_H-i-s') . '.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Get log statistics
     */
    private function getLogStatistics(array $filters = [])
    {
        $baseQuery = ActivityLog::query();

        // Apply date filters for stats
        if (!empty($filters['date_from'])) {
            $baseQuery->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $baseQuery->whereDate('created_at', '<=', $filters['date_to']);
        }

        return [
            'total_logs' => (clone $baseQuery)->count(),
            'today_logs' => (clone $baseQuery)->whereDate('created_at', today())->count(),
            'this_week_logs' => (clone $baseQuery)->where('created_at', '>=', now()->startOfWeek())->count(),
            'this_month_logs' => (clone $baseQuery)->where('created_at', '>=', now()->startOfMonth())->count(),
            'login_logs' => (clone $baseQuery)->where('action', 'LIKE', '%login%')->count(),
            'article_logs' => (clone $baseQuery)->where('action', 'LIKE', '%article%')->count(),
            'user_logs' => (clone $baseQuery)->where('action', 'LIKE', '%user%')->count(),
            'system_logs' => (clone $baseQuery)->where('action', 'LIKE', '%system%')->count(),
        ];
    }

    /**
     * Get filter options for dropdowns
     */
    private function getFilterOptions()
    {
        return [
            'users' => User::where('is_active', true)
                ->orderBy('email')
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->username ?: $user->email,
                        'email' => $user->email,
                    ];
                }),
            'actions' => ActivityLog::select('action')
                ->distinct()
                ->orderBy('action')
                ->pluck('action')
                ->take(50),
            'date_ranges' => [
                'today' => 'Hôm nay',
                'yesterday' => 'Hôm qua',
                'this_week' => 'Tuần này',
                'last_week' => 'Tuần trước',
                'this_month' => 'Tháng này',
                'last_month' => 'Tháng trước',
                'custom' => 'Tùy chọn',
            ],
        ];
    }

    /**
     * Generate CSV content from logs
     */
    private function generateCsvContent($logs)
    {
        $csv = "ID,Hành động,Mô tả,Người dùng,Email,IP,Thời gian,Loại đối tượng,ID đối tượng\n";

        foreach ($logs as $log) {
            $userName = $log->user ? ($log->user->username ?: $log->user->email) : 'System';
            $userEmail = $log->user ? $log->user->email : '';

            $csv .= implode(',', [
                $log->id,
                '"' . str_replace('"', '""', $log->action) . '"',
                '"' . str_replace('"', '""', $log->description) . '"',
                '"' . str_replace('"', '""', $userName) . '"',
                '"' . str_replace('"', '""', $userEmail) . '"',
                $log->ip_address ?: '',
                $log->created_at->format('Y-m-d H:i:s'),
                $log->entity_type ?: '',
                $log->entity_id ?: '',
            ]) . "\n";
        }

        // Add BOM for proper UTF-8 encoding in Excel
        return "\xEF\xBB\xBF" . $csv;
    }
}
