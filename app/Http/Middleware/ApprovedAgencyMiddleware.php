<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApprovedAgencyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isApprovedAgency()) {
            abort(403, 'Your agency account must be approved to perform this action.');
        }
        return $next($request);
    }
}
