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
        if (!auth()->user()->isApprovedAgency()) {
            return redirect()->route('agency.pending')
                ->with('warning', 'Your agency account is pending approval.');
        }
        return $next($request);
    }
}
