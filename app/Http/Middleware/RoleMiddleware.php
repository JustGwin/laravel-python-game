<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  string  $role  Expected role: 'admin' or 'player'
     */
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== $role) {
            // ถ้า role ไม่ตรง → redirect ไปหน้าของ role ตัวเอง
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('game.index');
        }

        return $next($request);
    }
}
