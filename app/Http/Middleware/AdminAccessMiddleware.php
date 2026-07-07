<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAccessMiddleware
{
    public function handle(Request $request, Closure $next, ?string $module = null, ?string $action = null): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')->withErrors(['login_error' => 'Vui lòng đăng nhập để truy cập trang quản trị.']);
        }

        $user = Auth::user();

        if (!$user->role) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login')->withErrors(['login_error' => 'Từ chối truy cập: Tài khoản thông thường không thể truy cập cổng thông tin này.']);
        }

        if ($user->status !== 'active') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login')->withErrors(['login_error' => 'Từ chối truy cập: Tài khoản của bạn đã bị khóa hoặc vô hiệu hóa.']);
        }

        if ($module && $action) {
            if (!$user->hasPermission($module, $action)) {
                abort(403, 'Thao tác không hợp lệ: Bạn không có quyền thực hiện chức năng này.');
            }
        }

        return $next($request);
    }
}
