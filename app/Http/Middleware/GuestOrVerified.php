<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GuestOrVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        // Allow all requests, regardless of email verification status.
        return $next($request);
    }
}
