<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $validator = $this->validateLogin($request);

        if ($validator->fails()) {
            return $this->sendFailedLoginResponse($request, $validator);
        }

        // Attempt to login
        $credentials = $this->credentials($request);

        if ($this->attemptLogin($request, $credentials)) {
            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate login request
     */
    protected function validateLogin(Request $request)
    {
        return Validator::make($request->all(), [
            $this->username() => 'required|string',
            'password' => 'required|string|min:6',
        ], [
            $this->username() . '.required' => 'Vui lòng nhập email hoặc username.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        ]);
    }

    /**
     * Get login credentials
     */
    protected function credentials(Request $request)
    {
        $login = $request->input($this->username());

        // Determine if login is email or username
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return [
            $field => $login,
            'password' => $request->input('password'),
            'is_active' => true, // Only allow active users
        ];
    }

    /**
     * Attempt to log the user into the application
     */
    protected function attemptLogin(Request $request, array $credentials)
    {
        return Auth::attempt($credentials, $request->filled('remember'));
    }

    /**
     * Send successful login response
     */
    protected function sendLoginResponse(Request $request)
    {
        $user = Auth::user();

        // Check if user has admin/editor role
        if (!$user->isAdmin() && !$user->isEditor()) {
            Auth::logout();

            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'login.failed.no_permission',
                'description' => 'Người dùng không có quyền truy cập admin',
                'ip_address' => $request->ip(),
                'metadata' => [
                    'user_roles' => $user->roles->pluck('name')->toArray(),
                ],
            ]);

            return $this->sendFailedLoginResponse($request, null, 'Bạn không có quyền truy cập vào khu vực này.');
        }

        // Update last login
        $user->updateLastLogin();

        // Log successful login
        ActivityLog::log(
            'user.login.success',
            'Đăng nhập thành công vào admin panel'
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'username' => $user->username,
                    'roles' => $user->roles->pluck('name'),
                    'permissions' => $user->roles->flatMap->permissions->pluck('name')->unique(),
                    'is_admin' => $user->isAdmin(),
                    'is_editor' => $user->isEditor(),
                ],
                'redirect' => $this->redirectPath(),
            ]);
        }

        return redirect()->intended($this->redirectPath());
    }

    /**
     * Send failed login response
     */
    protected function sendFailedLoginResponse(Request $request, $validator = null, $message = null)
    {
        // Log failed login attempt
        ActivityLog::create([
            'user_id' => null,
            'action' => 'login.failed',
            'description' => 'Thử đăng nhập thất bại',
            'ip_address' => $request->ip(),
            'metadata' => [
                'login_field' => $request->input($this->username()),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()->toISOString(),
            ],
        ]);

        $errorMessage = $message ?: 'Thông tin đăng nhập không chính xác.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'errors' => $validator ? $validator->errors() : ['login' => [$errorMessage]],
            ], 422);
        }

        throw ValidationException::withMessages([
            $this->username() => [$errorMessage],
        ]);
    }

    /**
     * Log the user out
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        // Log logout activity
        ActivityLog::log(
            'user.logout',
            'Đăng xuất khỏi hệ thống'
        );

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đăng xuất thành công',
                'redirect' => route('admin.login'),
            ]);
        }

        return redirect()->route('admin.login')->with('message', 'Đã đăng xuất thành công.');
    }

    /**
     * Get the login username (email or username)
     */
    public function username()
    {
        return 'login'; // We accept both email and username
    }

    /**
     * Where to redirect users after login
     */
    public function redirectPath()
    {
        return route('admin.dashboard');
    }
}
