<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMazadAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Check if mazad_auth is 1
            if (Auth::user()->auth_mazad) {
                return $next($request);
            } else {
                return response()->json(['message' => 'Unauthenticated-Mazad'], 401);
            }
        }

        return response()->json(['message' => 'Unauthenticated'], 401);
    }
}
