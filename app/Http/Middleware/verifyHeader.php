<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class verifyHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('X-Powered-By') !== 'Electra Engginering') {
            return response()->json(['error' => 'Unauthorized. Missing or invalid X-Powered-By.'], 401);
        }
        return $next($request);
    }
}
