<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class Admin
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->user() && $request->user()->role_id === User::ADMIN){
            return $next($request);
        }
        
        abort(403);
    }
}
