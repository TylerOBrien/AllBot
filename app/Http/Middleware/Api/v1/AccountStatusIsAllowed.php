<?php

namespace App\Http\Middleware\Api\v1;

use Closure;

use App\Enums\Account\AccountStatus;
use App\Exceptions\Api\v1\Account\AccountSuspended;

use Illuminate\Http\Request;

class AccountStatusIsAllowed
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

        switch ($user->account?->status) {
        case AccountStatus::Suspended:
            throw new AccountSuspended;
        }

        return $next($request);
    }
}
