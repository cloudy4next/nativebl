<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {

        if (Auth::check() && Auth::user()->hasPermission($permission)) {
            return $next($request);
        }

        if ($request->session()->get('applicationID') == config('nativebl.base.toffee_analytics_application_id')) {
            return to_route(config('nativebl.base.toffee_home_route'))->with('error', 'UNAUTHORIZED');
        }
        return to_route(config('nativebl.base.tigerweb_home_route'))->with('error', 'UNAUTHORIZED');
    }
}
