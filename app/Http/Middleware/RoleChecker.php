<?php

namespace App\Http\Middleware;

use Closure;

class RoleChecker
{
    /**
     * Handle an incoming request and check if user is authenticated and authorized
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // make sure request is authenticated
        if($request->user() === null)
        {
            return response('Insufficient permissions', 401);
        }

        // retrieve route actions
        $actions = $request->route()->getAction();

        $roles = isset($actions['roles']) ? $actions['roles'] : null;

        // print_r($roles); exit();

        // check the roles, if any
        if($request->user()->hasAnyRoles($roles) || !$roles)
        {
            return $next($request);
        }

        return response('Insufficient permissions', 401);
    }
}
