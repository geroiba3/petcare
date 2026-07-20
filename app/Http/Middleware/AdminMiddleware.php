<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        $isAdmin = false;
        if ($user) {
            if (method_exists($user, 'isAdmin')) {
                $isAdmin = (bool) $user->isAdmin();
            } elseif (isset($user->is_admin)) {
                $isAdmin = (bool) $user->is_admin;
            } elseif (isset($user->role) && $user->role === 'admin') {
                $isAdmin = true;
            }
        }

        if (!Auth::check() || !$user || !$isAdmin) {
            abort(403, 'No tenés permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}