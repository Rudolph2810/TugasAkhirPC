<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        

         // ✅ Cek user active
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Akun Anda belum diaktifkan. Silakan hubungi Admin.');
        }

         // ✅ Cek role user ada di daftar yang diizinkan
        $allowed = false;
        foreach ($roles as $role) {
            if ($user->role === $role) {
                $allowed = true;
                break;
            }
        }

        if (!$allowed) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini. Hanya role: ' . implode(', ', $roles) . ' yang diizinkan.');
        }

    

        return $next($request);
    }
}