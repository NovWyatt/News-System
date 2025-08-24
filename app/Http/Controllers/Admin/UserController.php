<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
        $this->middleware('permission:users.view')->only(['index', 'show']);
        $this->middleware('permission:users.create')->only(['create', 'store']);
        $this->middleware('permission:users.edit')->only(['edit', 'update']);
        $this->middleware('permission:users.delete')->only(['destroy']);
        $this->middleware('permission:users.manage')->except(['index', 'show']);
    }

    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        // Validate filter parameters
        $filters = $request->validate([
            'search' => 'nullable|string|max:255',
            'role' => 'nullable|string|exists:roles,name',
            'status' => 'nullable|in:active,inactive',
            'sort' => 'nullable|in:name,email,created_at,last_login_at',
            'order' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:10|max:100',
        ]);

        // Build query
        $query = User::with(['roles']);

        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('email', 'LIKE', "%{$search}%")
                  ->orWhere('username', 'LIKE', "%{$search}%");
            });
        }

        // Apply role filter
        if (!empty($filters['role'])) {
            $query->whereHas('roles', function ($q) use ($filters) {
                $q->where('name', $filters['role']);
            });
        }

        // Apply status filter
        if (isset($filters['status'])) {
            $isActive = $filters['status'] === 'active';
            $query->where('is_active', $isActive);
        }

        // Apply sorting
        $sortField = $filters['sort'] ?? 'created_at';
        $sortOrder = $filters['order'] ?? 'desc';

        // Handle special sort cases
        if ($sortField === 'name') {
            // Sort by username first, then email if no username
            $query->orderByRaw("COALESCE(username, email) {$sortOrder}");
        } else {
            $query->orderBy($sortField, $sortOrder);
        }

        // Get users with pagination
        $perPage = $filters['per_page'] ?? 20;
        $users = $query->paginate($perPage)->withQueryString();

        // Get statistics
        $stats = $this->getUserStatistics();

        // Get filter options
        $filterOptions = $this->getFilterOptions();

        // Prepare data
        $data = [
            'users' => $users,
            'stats' => $stats,
            'filters' => $filters,
            'filterOptions' => $filterOptions,
            'currentUser' => auth()->user(),
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        }

        return view('admin.users.index', $data);
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = Role::all();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'roles' => $roles,
            ]);
        }

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'username' => 'nullable|string|max:50|unique:users,username',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
            'is_active' => 'boolean',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',
            'username.unique' => 'Username này đã được sử dụng.',
            'username.max' => 'Username không được vượt quá 50 ký tự.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'roles.required' => 'Vui lòng chọn ít nhất một vai trò.',
            'roles.min' => 'Vui lòng chọn ít nhất một vai trò.',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        // Create user
        $userData = [
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => $request->boolean('is_active', true),
            'email_verified_at' => now(), // Auto verify admin-created users
        ];

        if ($request->filled('username')) {
            $userData['username'] = $request->username;
        }

        $user = User::create($userData);

        // Assign roles
        $user->roles()->attach($request->roles, [
            'assigned_by' => auth()->id(),
            'assigned_at' => now(),
        ]);

        // Log activity
        ActivityLog::log(
            'user.created',
            "Tạo người dùng mới: {$user->email}",
            $user,
            [
                'roles' => Role::whereIn('id', $request->roles)->pluck('name')->toArray(),
                'is_active' => $user->is_active,
            ]
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tạo người dùng thành công.',
                'user' => $user->load('roles'),
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', "Tạo người dùng {$user->email} thành công.");
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $user->load(['roles.permissions', 'articles']);

        // Get user statistics
        $userStats = [
            'total_articles' => $user->articles()->count(),
            'published_articles' => $user->articles()->where('status', 'published')->count(),
            'draft_articles' => $user->articles()->where('status', 'draft')->count(),
            'total_activity_logs' => $user->activityLogs()->count(),
            'recent_activity_logs' => $user->activityLogs()->latest()->limit(10)->get(),
        ];

        $userData = [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'is_active' => $user->is_active,
            'email_verified_at' => $user->email_verified_at?->format('d/m/Y H:i:s'),
            'last_login_at' => $user->last_login_at?->format('d/m/Y H:i:s'),
            'created_at' => $user->created_at->format('d/m/Y H:i:s'),
            'roles' => $user->roles,
            'permissions' => $user->roles->flatMap->permissions->unique('id'),
            'stats' => $userStats,
            'can_edit' => auth()->user()->hasPermission('users.edit'),
            'can_delete' => auth()->user()->hasPermission('users.delete') && $user->id !== auth()->id(),
        ];

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'user' => $userData,
            ]);
        }

        return view('admin.users.show', compact('userData'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        // Prevent editing self through this interface
        if ($user->id === auth()->id()) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể chỉnh sửa tài khoản của chính mình qua giao diện này.',
                ], 403);
            }

            return redirect()->route('admin.profile.show')
                ->with('info', 'Vui lòng sử dụng trang Hồ sơ cá nhân để chỉnh sửa tài khoản của bạn.');
        }

        $user->load('roles');
        $roles = Role::all();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'user' => $user,
                'roles' => $roles,
            ]);
        }

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        // Prevent editing self through this interface
        if ($user->id === auth()->id()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể chỉnh sửa tài khoản của chính mình qua giao diện này.',
                ], 403);
            }

            return redirect()->route('admin.profile.show')
                ->with('info', 'Vui lòng sử dụng trang Hồ sơ cá nhân để chỉnh sửa tài khoản của bạn.');
        }

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'username' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
            'is_active' => 'boolean',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',
            'username.unique' => 'Username này đã được sử dụng.',
            'username.max' => 'Username không được vượt quá 50 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'roles.required' => 'Vui lòng chọn ít nhất một vai trò.',
            'roles.min' => 'Vui lòng chọn ít nhất một vai trò.',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        // Store old data for logging
        $oldData = [
            'email' => $user->email,
            'username' => $user->username,
            'is_active' => $user->is_active,
            'roles' => $user->roles->pluck('name')->toArray(),
        ];

        // Update user data
        $userData = [
            'email' => $request->email,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->filled('username')) {
            $userData['username'] = $request->username;
        } else {
            $userData['username'] = null;
        }

        // Update password if provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Update roles
        $user->roles()->sync($request->roles);

        // Log activity
        ActivityLog::log(
            'user.updated',
            "Cập nhật người dùng: {$user->email}",
            $user,
            [
                'old_data' => $oldData,
                'new_data' => [
                    'email' => $user->email,
                    'username' => $user->username,
                    'is_active' => $user->is_active,
                    'roles' => Role::whereIn('id', $request->roles)->pluck('name')->toArray(),
                ],
                'password_changed' => $request->filled('password'),
            ]
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật người dùng thành công.',
                'user' => $user->fresh()->load('roles'),
            ]);
        }

        return redirect()->route('admin.users.show', $user)
            ->with('success', "Cập nhật người dùng {$user->email} thành công.");
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy(Request $request, User $user)
    {
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa tài khoản của chính mình.',
                ], 403);
            }

            return back()->withErrors(['delete' => 'Không thể xóa tài khoản của chính mình.']);
        }

        // Check if user has articles
        $articleCount = $user->articles()->count();

        if ($articleCount > 0 && !$request->boolean('force_delete')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Người dùng này có {$articleCount} bài viết. Vui lòng xác nhận việc xóa bằng cách đánh dấu force_delete.",
                    'article_count' => $articleCount,
                    'requires_confirmation' => true,
                ], 422);
            }

            return back()->withErrors(['delete' => "Người dùng này có {$articleCount} bài viết. Bạn có chắc chắn muốn xóa?"]);
        }

        // Store user data for logging
        $userData = [
            'id' => $user->id,
            'email' => $user->email,
            'username' => $user->username,
            'roles' => $user->roles->pluck('name')->toArray(),
            'article_count' => $articleCount,
        ];

        $userEmail = $user->email;

        // Delete user (this will cascade delete related data)
        $user->delete();

        // Log activity
        ActivityLog::log(
            'user.deleted',
            "Xóa người dùng: {$userEmail}",
            null,
            $userData
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Xóa người dùng {$userEmail} thành công.",
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', "Xóa người dùng {$userEmail} thành công.");
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(Request $request, User $user)
    {
        // Prevent deactivating self
        if ($user->id === auth()->id()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể thay đổi trạng thái tài khoản của chính mình.',
                ], 403);
            }

            return back()->withErrors(['status' => 'Không thể thay đổi trạng thái tài khoản của chính mình.']);
        }

        $oldStatus = $user->is_active;
        $newStatus = !$oldStatus;

        $user->update(['is_active' => $newStatus]);

        $statusText = $newStatus ? 'kích hoạt' : 'vô hiệu hóa';

        // Log activity
        ActivityLog::log(
            'user.status_changed',
            "Thay đổi trạng thái người dùng {$user->email}: {$statusText}",
            $user,
            [
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ]
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Đã {$statusText} người dùng {$user->email}.",
                'new_status' => $newStatus,
            ]);
        }

        return back()->with('success', "Đã {$statusText} người dùng {$user->email}.");
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Log activity
        ActivityLog::log(
            'user.password_reset',
            "Reset mật khẩu cho người dùng: {$user->email}",
            $user
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Reset mật khẩu cho {$user->email} thành công.",
            ]);
        }

        return back()->with('success', "Reset mật khẩu cho {$user->email} thành công.");
    }

    /**
     * Get user statistics
     */
    private function getUserStatistics()
    {
        return [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'inactive_users' => User::where('is_active', false)->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'unverified_users' => User::whereNull('email_verified_at')->count(),
            'admin_users' => User::whereHas('roles', function ($q) {
                $q->where('name', 'admin');
            })->count(),
            'editor_users' => User::whereHas('roles', function ($q) {
                $q->where('name', 'editor');
            })->count(),
            'recent_users' => User::where('created_at', '>=', now()->subDays(30))->count(),
        ];
    }

    /**
     * Get filter options
     */
    private function getFilterOptions()
    {
        return [
            'roles' => Role::orderBy('display_name')->get(['name', 'display_name']),
            'statuses' => [
                'active' => 'Hoạt động',
                'inactive' => 'Không hoạt động',
            ],
            'sort_options' => [
                'created_at' => 'Ngày tạo',
                'name' => 'Tên',
                'email' => 'Email',
                'last_login_at' => 'Đăng nhập cuối',
            ],
        ];
    }
}
