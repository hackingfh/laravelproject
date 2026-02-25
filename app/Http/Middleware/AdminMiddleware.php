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
        if (!$user || !$user->is_admin) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Admin access required'], 403);
            }
            return redirect()->route('login')->with('error', 'Admin access required');
        }

        return $next($request);
    }
}
