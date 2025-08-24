<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Show user profile
     */
    public function show()
    {
        $user = auth()->user();

        $userData = [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'display_name' => $user->username ?: explode('@', $user->email)[0],
            'is_active' => $user->is_active,
            'last_login_at' => $user->last_login_at?->format('d/m/Y H:i:s'),
            'created_at' => $user->created_at->format('d/m/Y H:i:s'),
            'email_verified_at' => $user->email_verified_at?->format('d/m/Y H:i:s'),
            'roles' => $user->roles->map(function ($role) {
                return [
                    'name' => $role->name,
                    'display_name' => $role->display_name,
                ];
            }),
            'permissions' => $user->roles->flatMap->permissions->unique('name')->map(function ($permission) {
                return [
                    'name' => $permission->name,
                    'display_name' => $permission->display_name,
                    'module' => $permission->module,
                ];
            }),
            'stats' => [
                'total_articles' => $user->articles()->count(),
                'published_articles' => $user->articles()->where('status', 'published')->count(),
                'draft_articles' => $user->articles()->where('status', 'draft')->count(),
            ],
        ];

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'user' => $userData,
            ]);
        }

        return view('admin.profile.show', compact('userData'));
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'nullable|string|max:50|unique:users,username,' . $user->id,
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',
            'username.max' => 'Username không được quá 50 ký tự.',
            'username.unique' => 'Username này đã được sử dụng.',
        ]);

        $oldData = $user->only(['email', 'username']);

        // Cập nhật dữ liệu
        $updateData = ['email' => $request->email];
        if ($request->filled('username')) {
            $updateData['username'] = $request->username;
        }

        $user->update($updateData);

        ActivityLog::log(
            'profile.updated',
            'Cập nhật thông tin cá nhân',
            $user,
            [
                'old_data' => $oldData,
                'new_data' => $user->fresh()->only(['email', 'username']),
            ]
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thông tin thành công.',
                'user' => $user->fresh(),
            ]);
        }

        return back()->with('success', 'Cập nhật thông tin thành công.');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mật khẩu hiện tại không chính xác.',
                    'errors' => ['current_password' => ['Mật khẩu hiện tại không chính xác.']],
                ], 422);
            }

            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        ActivityLog::log(
            'password.changed',
            'Thay đổi mật khẩu thành công'
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Mật khẩu đã được thay đổi thành công.',
            ]);
        }

        return back()->with('success', 'Mật khẩu đã được thay đổi thành công.');
    }
}
