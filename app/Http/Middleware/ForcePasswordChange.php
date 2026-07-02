<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->must_change_password) {
            // Allow access to the password change routes and logout
            if (! $request->routeIs('password.change.force') && 
                ! $request->routeIs('password.update.force') && 
                ! $request->routeIs('logout')) {
                return redirect()->route('password.change.force');
            }
        }

        return $next($request);
    }
}
