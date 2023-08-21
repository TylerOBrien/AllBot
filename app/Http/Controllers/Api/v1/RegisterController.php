<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\Register;
use App\Http\Resources\Api\v1\Token\TokenResource;
use App\Models\{ Identity, Token, Secret, User };

class RegisterController extends Controller
{
    /**
     * Create a new account for a guest.
     *
     * @param  \App\Http\Requests\Api\v1\Auth\Register  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Register $request)
    {
        $fields = $request->validated();
        $user = User::createWithAccount($fields)->fresh();
        $identity = Identity::createFromFields($user, $fields);
        $pat = Token::createFromIdentity($identity);
        $token = new TokenResource($pat);
        $secret = Secret::createFromIdentity($identity, $fields);

        return response(compact('token', 'user', 'identity', 'secret'), 201);
    }
}
