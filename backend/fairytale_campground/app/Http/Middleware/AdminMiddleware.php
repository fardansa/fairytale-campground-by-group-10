<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || ($user->role ?? null) !== 'admin') {
            return response()->json([
                'message' => 'Akses dilarang: Admin only'
            ], 403);
        }

        return $next($request);
    }
}
