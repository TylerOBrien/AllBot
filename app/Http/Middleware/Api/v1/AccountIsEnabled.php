<?php

namespace App\Http\Middleware\Api\v1;

use Closure;

use App\Exceptions\Api\v1\Account\AccountDisabled;

use Illuminate\Http\Request;

class AccountIsEnabled
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

        if (! $user->account?->is_enabled) {
            throw new AccountDisabled;
        }

        return $next($request);
    }
}
