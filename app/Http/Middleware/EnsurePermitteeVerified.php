<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePermitteeVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user()->email_verified_at && auth()->user()->usertype === 'permittee') {
            // redirect to change password)
            return redirect()->route('password.request')->with('message', 'Please change your password before proceeding.');
        }
        return $next($request);
    }
}
