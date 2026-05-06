<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AgencyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isAgency()) {
            abort(403, 'Access denied. Agency account required.');
        }
        // Allow unapproved agencies to access dashboard, inquiries, etc.
        // Package creation restriction is handled separately
        return $next($request);
    }
}
