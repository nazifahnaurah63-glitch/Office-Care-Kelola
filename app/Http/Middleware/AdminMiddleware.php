<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string $permission = ''
    ): Response {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $role = $request->user()->role;

        if (!$role) {
            abort(403, 'Role pengguna tidak ditemukan.');
        }

        // Admin selalu memiliki akses penuh
        if ($role->name === 'Admin') {
            return $next($request);
        }

        // Mendukung beberapa permission dengan pemisah |
        $permissions = explode('|', $permission);

        foreach ($permissions as $item) {
            if ($item !== '' && $role->hasPermission($item)) {
                return $next($request);
            }
        }

        abort(403, 'Kamu tidak memiliki izin untuk mengakses halaman ini.');
    }
}
