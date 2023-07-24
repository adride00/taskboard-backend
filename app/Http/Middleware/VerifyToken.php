<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next)
    {
        $currentUrl = $request->getPathInfo();
        if ($currentUrl === '/api/login' || $currentUrl === '/api/signup') {
            return $next($request);
        }
        if (!$request->hasHeader('Authorization')) {
            return response()->json(['error' => 'Token no valido'], 401);
        }

        return $next($request);
    }
}
