<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class Gamer
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->user() && $request->user()->role_id === User::GAMER){
            return $next($request);
        }
        
        abort(403);
    }
}
