<?php

namespace App\Http\Middleware\Api\v1;

use Closure;

use App\Exceptions\Api\v1\Identity\IdentityNotVerified;

use Illuminate\Http\Request;

class IdentityIsVerified
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
        /** @var \App\Models\User */
        $user = auth()->user();

        if (! $user->is_identified) {
            throw new IdentityNotVerified;
        }

        return $next($request);
    }
}
