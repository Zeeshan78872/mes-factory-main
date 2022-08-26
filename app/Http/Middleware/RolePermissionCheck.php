<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolePermissionCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure$next, $permission)
    {
        if( Auth::user()->isPermissionAllowed($permission) )  {
            return $next($request);
        }
        return redirect('no-access');
    }
}
