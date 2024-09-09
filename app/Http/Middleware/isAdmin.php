<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $userRoleId = Auth::user()->role_id;

            
            $role = DB::table('roles')->where('id', $userRoleId)->first();

            if ($role && ($role->name == 'Admin')) {
                return $next($request);
            }
        }

        // If the user is not authenticated or does not have the correct role, abort with 404
        return abort(404);
    } 
}
