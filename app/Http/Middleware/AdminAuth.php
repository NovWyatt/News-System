<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class AdminAuth
{
    /**
     * Handle an incoming request
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập để tiếp tục.',
                    'redirect' => route('admin.login'),
                ], 401);
            }

            return redirect()->route('admin.login');
        }

        $user = Auth::user();

        // Check if user is active
        if (!$user->is_active) {
            Auth::logout();

            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'login.blocked.inactive',
                'description' => 'Tài khoản bị vô hiệu hóa cố gắng truy cập',
                'ip_address' => $request->ip(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản đã bị vô hiệu hóa.',
                    'redirect' => route('admin.login'),
                ], 403);
            }

            return redirect()->route('admin.login')
                ->withErrors(['login' => 'Tài khoản đã bị vô hiệu hóa.']);
        }

        // Check if user has admin or editor role
        if (!$user->isAdmin() && !$user->isEditor()) {
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'access.denied.no_role',
                'description' => 'Người dùng không có quyền truy cập admin panel',
                'ip_address' => $request->ip(),
                'metadata' => [
                    'requested_url' => $request->fullUrl(),
                    'user_roles' => $user->roles->pluck('name')->toArray(),
                ],
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền truy cập vào khu vực này.',
                ], 403);
            }

            Auth::logout();
            return redirect()->route('admin.login')
                ->withErrors(['access' => 'Bạn không có quyền truy cập vào khu vực này.']);
        }

        return $next($request);
    }
}
