<?php

namespace App\Http\Controllers\Api;

use App\Guards\Api\v1\ApiGuard;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\Login;
use App\Http\Resources\Api\v1\Token\TokenResource;
use App\Models\Token;

class LoginController extends Controller
{
    /**
     * Attempt to authenticate the guest using the given credentials.
     *
     * @param  \App\Http\Requests\Api\v1\Auth\Login  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Login $request)
    {
        $fields = $request->validated();
        $identity = ApiGuard::get()->attempt($fields);
        $user = $identity->user;
        $pat = Token::createFromIdentity($identity);
        $token = new TokenResource($pat);

        return response(compact('token', 'identity', 'user'), 201);
    }
}
