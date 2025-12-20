<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->must_change_password) {
            // cho phép vào các route đổi mật khẩu + logout
            if (! $request->routeIs('profile.edit', 'profile.update', 'logout')) {
                return redirect()->route('profile.edit')
                    ->with('warning', 'Vui lòng đổi mật khẩu trước khi tiếp tục.');
            }
        }

        return $next($request);
    }
}
