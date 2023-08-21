<?php

namespace App\Http\Middleware\Api\v1;

use Closure;

use App\Guards\Api\v1\ApiGuard;

use Illuminate\Http\Request;

class BearerToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        ApiGuard::get()->parseToken($request);

        return $next($request);
    }
}
